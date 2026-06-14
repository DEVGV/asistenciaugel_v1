<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AltasTrabajadores;
use App\Models\Conasis\ConasisDetalleHorarios;
use App\Models\Conasis\ConasisHorariosTrabajador;
use App\Models\Conasis\ConasisLocalesMarcacion;
use App\Models\Conasis\ConasisMarcaciones;
use App\Models\Conasis\MobileBiometricCredential;
use App\Models\Trabajador;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MobileController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'dni' => ['required', 'string', 'max:20'],
            'password' => ['required', 'string'],
        ]);

        $trabajador = Trabajador::query()
            ->where('activo', true)
            ->whereHas('persona', fn ($query) => $query->where('docIdentidad', $validated['dni']))
            ->first();

        if (! $trabajador) {
            return response()->json([
                'message' => 'No existe trabajador activo para el DNI ingresado.',
            ], 422);
        }

        $user = User::query()->where('trabajador_id', (string) $trabajador->id)->first();

        if (! $user) {
            return response()->json([
                'message' => 'El trabajador no tiene usuario movil asociado en auth.users.',
            ], 422);
        }

        if (! Hash::check($validated['password'], $user->password)) {
            return response()->json(['message' => 'Credenciales invalidas.'], 422);
        }

        Auth::login($user);
        $request->session()->regenerate();
        $request->setUserResolver(fn () => $user);

        return $this->me($request);
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Sesion movil cerrada.']);
    }

    public function me(Request $request): JsonResponse
    {
        $trabajador = $this->currentTrabajador($request);

        if (! $trabajador) {
            return response()->json(['message' => 'El usuario no tiene trabajador asociado.'], 404);
        }

        $today = today();
        $altas = $this->activeAltas($trabajador, $today)->get();
        $credential = $this->credentialFor($trabajador);

        return response()->json([
            'data' => [
                'user' => [
                    'id' => $request->user()->id,
                    'name' => $request->user()->name,
                    'email' => $request->user()->email,
                ],
                'teacher' => [
                    'id' => $trabajador->id,
                    'codigo' => $trabajador->codigo,
                    'documento' => $trabajador->persona?->docIdentidad,
                    'nombre_completo' => $trabajador->persona?->nombre_completo,
                ],
                'mobile_biometric' => $this->formatCredential($credential),
                'assignments' => $altas->map(fn (AltasTrabajadores $alta): array => [
                    'alta_trabajador_id' => $alta->id,
                    'institucion_educativa_id' => $alta->institucionEducativa_id,
                    'institucion_nombre' => $alta->institucionEducativa?->nombreLegal,
                    'fecha_inicio' => $alta->fechaInicio,
                    'fecha_fin' => $alta->fechaFin,
                    'local_marcacion' => $this->formatLocalMarcacion(
                        $this->activeLocalMarcacion($trabajador, $alta, $today)
                    ),
                ])->values(),
            ],
        ]);
    }

    public function enrollFace(Request $request): JsonResponse
    {
        $trabajador = $this->requireTrabajador($request);
        $validated = $request->validate([
            'face_image_base64' => ['required', 'string'],
            'face_embedding' => ['required', 'array', 'min:64', 'max:512'],
            'face_embedding.*' => ['required', 'numeric'],
        ]);

        $template = $this->buildFaceTemplate($validated['face_image_base64']);
        $embedding = $this->normalizeEmbedding($validated['face_embedding']);

        $credential = MobileBiometricCredential::query()->updateOrCreate(
            ['trabajador_id' => $trabajador->id],
            [
                'face_status' => MobileBiometricCredential::STATUS_APPROVED,
                'face_template' => $template,
                'face_threshold' => 4,
                'face_embedding' => $embedding,
                'face_similarity_threshold' => 0.6,
                'face_enrolled_at' => now(),
                'face_approved_at' => now(),
                'failed_attempts' => 0,
                'last_face_distance' => null,
                'last_face_similarity' => null,
                'blocked_until' => null,
                'created_by' => $request->user()->id,
                'updated_by' => $request->user()->id,
            ]
        );

        return response()->json([
            'data' => [
                'biometric' => $this->formatCredential($credential),
                'face_template_length' => strlen($template),
            ],
        ], 201);
    }

    public function enableLocalBiometric(Request $request): JsonResponse
    {
        $trabajador = $this->requireTrabajador($request);
        $credential = $this->credentialFor($trabajador);

        $credential->forceFill([
            'local_biometric_enabled' => true,
            'local_biometric_enabled_at' => now(),
            'updated_by' => $request->user()->id,
        ])->save();

        return response()->json(['data' => $this->formatCredential($credential)]);
    }

    public function prevalidate(Request $request): JsonResponse
    {
        $trabajador = $this->requireTrabajador($request);
        $validated = $request->validate([
            'alta_trabajador_id' => ['nullable', 'integer'],
        ]);

        $context = $this->markingContext($trabajador, $validated['alta_trabajador_id'] ?? null);

        return response()->json([
            'data' => [
                'allowed' => $context['allowed'],
                'checks' => $context['checks'],
                'assignment' => $context['alta'] ? [
                    'alta_trabajador_id' => $context['alta']->id,
                    'institucion_educativa_id' => $context['alta']->institucionEducativa_id,
                ] : null,
                'local_marcacion' => $this->formatLocalMarcacion($context['localMarcacion']),
                'schedule' => $context['schedule'],
            ],
        ]);
    }

    public function storeMark(Request $request): JsonResponse
    {
        $trabajador = $this->requireTrabajador($request);
        $validated = $request->validate([
            'alta_trabajador_id' => ['nullable', 'integer'],
            'biometric_method' => ['required', Rule::in(['face', 'local_device'])],
            'biometric_passed' => ['nullable', 'boolean'],
            'face_image_base64' => ['nullable', 'string'],
            'face_embedding' => ['nullable', 'array', 'min:64', 'max:512'],
            'face_embedding.*' => ['required_with:face_embedding', 'numeric'],
            'utm_huso' => ['nullable', 'numeric'],
            'utm_base' => ['nullable', 'string', 'max:10'],
            'utm_x_este' => ['nullable', 'numeric'],
            'utm_y_norte' => ['nullable', 'numeric'],
        ]);

        $credential = $this->credentialFor($trabajador);
        $context = $this->markingContext($trabajador, $validated['alta_trabajador_id'] ?? null);

        if (! $context['allowed']) {
            return response()->json([
                'message' => 'La marcacion movil no esta habilitada para este trabajador.',
                'checks' => $context['checks'],
            ], 422);
        }

        if ($credential->blocked_until && $credential->blocked_until->isFuture()) {
            return response()->json([
                'message' => 'La validacion biometrica se encuentra bloqueada temporalmente.',
                'biometric' => $this->formatCredential($credential),
            ], 423);
        }

        if ($validated['biometric_method'] === 'local_device' && ! $credential->local_biometric_enabled) {
            return response()->json(['message' => 'La biometria local del dispositivo no esta habilitada.'], 422);
        }

        $isBiometricValid = false;
        $lastDistance = null;
        $lastSimilarity = null;

        if ($validated['biometric_method'] === 'face') {
            if (! $credential->face_embedding) {
                return response()->json(['message' => 'No existe plantilla facial enrolada para este docente.'], 422);
            }

            if (! filled($validated['face_image_base64'] ?? null)) {
                return response()->json(['message' => 'Debe capturar un rostro para validar la marcacion.'], 422);
            }

            if (! filled($validated['face_embedding'] ?? null)) {
                return response()->json(['message' => 'Debe enviar la plantilla facial para comparar la marcacion.'], 422);
            }

            $probeTemplate = $this->buildFaceTemplate($validated['face_image_base64']);
            $lastDistance = $credential->face_template
                ? $this->hammingDistance($credential->face_template, $probeTemplate)
                : null;
            $probeEmbedding = $this->normalizeEmbedding($validated['face_embedding']);
            $lastSimilarity = $this->cosineSimilarity($credential->face_embedding, $probeEmbedding);
            $isBiometricValid = $lastSimilarity >= ($credential->face_similarity_threshold ?? 0.6);
        } else {
            $isBiometricValid = (bool) ($validated['biometric_passed'] ?? false);
        }

        if (! $isBiometricValid) {
            $this->registerFailedAttempt(
                $credential,
                $request->user()->id,
                $lastDistance,
                $lastSimilarity
            );

            return response()->json([
                'message' => 'La validacion biometrica no fue superada.',
                'biometric' => $this->formatCredential($credential->refresh()),
            ], 422);
        }

        $credential->forceFill([
            'failed_attempts' => 0,
            'blocked_until' => null,
            'last_verified_at' => now(),
            'last_face_distance' => $lastDistance,
            'last_face_similarity' => $lastSimilarity,
            'updated_by' => $request->user()->id,
        ])->save();

        $mark = DB::transaction(function () use ($context, $request, $trabajador, $validated): ConasisMarcaciones {
            return ConasisMarcaciones::query()->create([
                'trabajador_id' => $trabajador->id,
                'altaTrabajador_id' => $context['alta']->id,
                'localMarcacion_id' => $context['localMarcacion']?->id,
                'codigo' => $this->mobileMarkCode($trabajador),
                'fechaMarcacion' => now(),
                'fechaRegistro' => now(),
                'tipo' => 'A',
                'medioMarcacion' => 'M',
                'procesado' => false,
                'utm_huso' => $validated['utm_huso'] ?? null,
                'utm_base' => $validated['utm_base'] ?? null,
                'utm_x_este' => $validated['utm_x_este'] ?? null,
                'utm_y_norte' => $validated['utm_y_norte'] ?? null,
                'created_by' => $request->user()->id,
            ]);
        });

        return response()->json([
            'data' => [
                'mark' => $this->formatMark($mark),
                'biometric' => $this->formatCredential($credential->refresh()),
            ],
        ], 201);
    }

    public function marks(Request $request): JsonResponse
    {
        $trabajador = $this->requireTrabajador($request);

        $marks = ConasisMarcaciones::query()
            ->where('trabajador_id', $trabajador->id)
            ->where('medioMarcacion', 'M')
            ->where('fechaMarcacion', '>=', now()->subDays(7))
            ->latest('fechaMarcacion')
            ->limit(20)
            ->get();

        return response()->json([
            'data' => $marks->map(fn (ConasisMarcaciones $mark): array => $this->formatMark($mark)),
        ]);
    }

    public function schedule(Request $request): JsonResponse
    {
        $trabajador = $this->requireTrabajador($request);
        $today = today();

        $horarios = ConasisHorariosTrabajador::query()
            ->where('trabajador_id', $trabajador->id)
            ->where('activo', true)
            ->whereDate('fechaInicio', '<=', $today)
            ->where(fn ($query) => $query->whereNull('fechaFin')->orWhereDate('fechaFin', '>=', $today))
            ->orderBy('fechaInicio')
            ->get();

        $detalles = ConasisDetalleHorarios::query()
            ->whereIn('horarioTrabajador_id', $horarios->pluck('id'))
            ->where('aplicar', true)
            ->orderBy('nroDia')
            ->orderBy('entHoraInicio')
            ->get();

        return response()->json([
            'data' => [
                'horarios' => $horarios->map(fn (ConasisHorariosTrabajador $horario): array => [
                    'id' => $horario->id,
                    'nombre' => $horario->nombre,
                    'tipo_horario' => $horario->tipoHorario,
                    'fecha_inicio' => $horario->fechaInicio,
                    'fecha_fin' => $horario->fechaFin,
                ])->values(),
                'detalles' => $detalles->map(fn (ConasisDetalleHorarios $detalle): array => [
                    'id' => $detalle->id,
                    'horario_trabajador_id' => $detalle->horarioTrabajador_id,
                    'dia_semana' => $detalle->diaSemana,
                    'nro_dia' => $detalle->nroDia,
                    'entrada_inicio' => $detalle->entHoraInicio,
                    'entrada_fin' => $detalle->entHoraFin,
                    'entrada_tolerancia' => $detalle->entTolerancia,
                    'salida_inicio' => $detalle->salHoraInicio,
                    'salida_fin' => $detalle->salHoraFin,
                    'salida_tolerancia' => $detalle->salTolerancia,
                ])->values(),
            ],
        ]);
    }

    private function currentTrabajador(Request $request): ?Trabajador
    {
        $trabajadorId = $request->user()->trabajador_id;

        if (! $trabajadorId) {
            return null;
        }

        return Trabajador::query()
            ->with('persona')
            ->where('activo', true)
            ->find($trabajadorId);
    }

    private function requireTrabajador(Request $request): Trabajador
    {
        $trabajador = $this->currentTrabajador($request);

        abort_if(! $trabajador, 404, 'El usuario no tiene trabajador activo asociado.');

        return $trabajador;
    }

    private function credentialFor(Trabajador $trabajador): MobileBiometricCredential
    {
        return MobileBiometricCredential::query()->firstOrCreate(
            ['trabajador_id' => $trabajador->id],
            ['face_status' => MobileBiometricCredential::STATUS_PENDING]
        );
    }

    private function activeAltas(Trabajador $trabajador, mixed $date)
    {
        return AltasTrabajadores::query()
            ->with('institucionEducativa')
            ->where('trabajador_id', $trabajador->id)
            ->whereDate('fechaInicio', '<=', $date)
            ->where(fn ($query) => $query->whereNull('fechaFin')->orWhereDate('fechaFin', '>=', $date))
            ->whereNull('fechaBaja')
            ->orderBy('fechaInicio');
    }

    private function activeLocalMarcacion(Trabajador $trabajador, AltasTrabajadores $alta, mixed $date): ?ConasisLocalesMarcacion
    {
        return ConasisLocalesMarcacion::query()
            ->with('localInstEduc.local')
            ->where('trabajador_id', $trabajador->id)
            ->where('altaTrabajador_id', $alta->id)
            ->whereDate('fechaInicio', '<=', $date)
            ->where(fn ($query) => $query->whereNull('fechaFin')->orWhereDate('fechaFin', '>=', $date))
            ->orderByDesc('fechaInicio')
            ->first();
    }

    private function markingContext(Trabajador $trabajador, ?int $altaTrabajadorId): array
    {
        $today = today();
        $altaQuery = $this->activeAltas($trabajador, $today);

        if ($altaTrabajadorId) {
            $altaQuery->whereKey($altaTrabajadorId);
        }

        $alta = $altaQuery->first();
        $localMarcacion = $alta ? $this->activeLocalMarcacion($trabajador, $alta, $today) : null;
        $credential = $this->credentialFor($trabajador);
        $schedule = $this->scheduleSnapshot($trabajador, $alta);

        $checks = [
            'trabajador_activo' => true,
            'alta_vigente' => filled($alta),
            'asignado_marcacion_movil' => filled($localMarcacion),
            'biometria_facial_aprobada' => $this->faceCredentialReady($credential),
            'metodo_biometrico_habilitado' => $this->faceCredentialReady($credential) || $credential->local_biometric_enabled,
            'biometria_no_bloqueada' => ! ($credential->blocked_until && $credential->blocked_until->isFuture()),
        ];

        return [
            'allowed' => $checks['trabajador_activo']
                && $checks['alta_vigente']
                && $checks['asignado_marcacion_movil']
                && $checks['metodo_biometrico_habilitado']
                && $checks['biometria_no_bloqueada'],
            'checks' => $checks,
            'alta' => $alta,
            'localMarcacion' => $localMarcacion,
            'schedule' => $schedule,
        ];
    }

    private function scheduleSnapshot(Trabajador $trabajador, ?AltasTrabajadores $alta): array
    {
        if (! $alta) {
            return ['has_active_schedule' => false, 'today_windows' => []];
        }

        $today = today();
        $currentDay = (int) $today->dayOfWeekIso;

        $horarios = ConasisHorariosTrabajador::query()
            ->where('trabajador_id', $trabajador->id)
            ->where('altaTrabajador_id', $alta->id)
            ->where('activo', true)
            ->whereDate('fechaInicio', '<=', $today)
            ->where(fn ($query) => $query->whereNull('fechaFin')->orWhereDate('fechaFin', '>=', $today))
            ->pluck('id');

        $windows = ConasisDetalleHorarios::query()
            ->whereIn('horarioTrabajador_id', $horarios)
            ->where('aplicar', true)
            ->where('nroDia', $currentDay)
            ->get()
            ->map(fn (ConasisDetalleHorarios $detalle): array => [
                'entrada_inicio' => $detalle->entHoraInicio,
                'entrada_fin' => $detalle->entHoraFin,
                'salida_inicio' => $detalle->salHoraInicio,
                'salida_fin' => $detalle->salHoraFin,
            ])
            ->values();

        return [
            'has_active_schedule' => $horarios->isNotEmpty(),
            'today_windows' => $windows,
        ];
    }

    private function registerFailedAttempt(
        MobileBiometricCredential $credential,
        int $userId,
        ?int $distance = null,
        ?float $similarity = null
    ): void {
        $attempts = $credential->failed_attempts + 1;

        $credential->forceFill([
            'failed_attempts' => $attempts,
            'face_status' => $attempts >= 3 ? MobileBiometricCredential::STATUS_BLOCKED : $credential->face_status,
            'blocked_until' => $attempts >= 3 ? now()->addMinutes(15) : null,
            'last_face_distance' => $distance,
            'last_face_similarity' => $similarity,
            'updated_by' => $userId,
        ])->save();
    }

    private function buildFaceTemplate(string $imageBase64): string
    {
        $binary = $this->decodeImageBase64($imageBase64);
        $resource = @imagecreatefromstring($binary);

        if (! $resource) {
            abort(422, 'No se pudo procesar la imagen facial enviada.');
        }

        $width = imagesx($resource);
        $height = imagesy($resource);
        $thumb = imagecreatetruecolor(8, 8);
        imagecopyresampled($thumb, $resource, 0, 0, 0, 0, 8, 8, $width, $height);

        $grayValues = [];
        for ($y = 0; $y < 8; $y++) {
            for ($x = 0; $x < 8; $x++) {
                $rgb = imagecolorat($thumb, $x, $y);
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;
                $grayValues[] = (int) round(($r + $g + $b) / 3);
            }
        }

        imagedestroy($thumb);
        imagedestroy($resource);

        $avg = array_sum($grayValues) / count($grayValues);
        $bits = '';
        foreach ($grayValues as $gray) {
            $bits .= $gray >= $avg ? '1' : '0';
        }

        return $bits;
    }

    private function decodeImageBase64(string $payload): string
    {
        $normalized = trim($payload);
        if (str_starts_with($normalized, 'data:')) {
            $parts = explode(',', $normalized, 2);
            $normalized = $parts[1] ?? '';
        }

        $binary = base64_decode($normalized, true);
        if ($binary === false || Str::length($binary) < 256) {
            abort(422, 'La imagen facial enviada no es valida.');
        }

        return $binary;
    }

    private function hammingDistance(string $templateA, string $templateB): int
    {
        $len = min(strlen($templateA), strlen($templateB));
        $distance = 0;

        for ($i = 0; $i < $len; $i++) {
            if ($templateA[$i] !== $templateB[$i]) {
                $distance++;
            }
        }

        return $distance + abs(strlen($templateA) - strlen($templateB));
    }

    private function normalizeEmbedding(array $embedding): array
    {
        $values = array_map(static fn (mixed $value): float => (float) $value, array_values($embedding));
        $norm = sqrt(array_sum(array_map(static fn (float $value): float => $value * $value, $values)));

        if (count($values) < 64 || $norm <= 0) {
            abort(422, 'La plantilla facial enviada no es valida.');
        }

        return array_map(static fn (float $value): float => round($value / $norm, 8), $values);
    }

    private function cosineSimilarity(array $storedEmbedding, array $probeEmbedding): float
    {
        if (count($storedEmbedding) !== count($probeEmbedding)) {
            abort(422, 'La plantilla facial no coincide con la dimension enrolada.');
        }

        $dot = 0.0;
        $normA = 0.0;
        $normB = 0.0;
        foreach ($storedEmbedding as $index => $storedValue) {
            $a = (float) $storedValue;
            $b = (float) $probeEmbedding[$index];
            $dot += $a * $b;
            $normA += $a * $a;
            $normB += $b * $b;
        }

        $denominator = sqrt($normA) * sqrt($normB);

        return $denominator > 0 ? round($dot / $denominator, 6) : 0.0;
    }

    private function mobileMarkCode(Trabajador $trabajador): string
    {
        $workerSuffix = str_pad((string) ($trabajador->id % 1000), 3, '0', STR_PAD_LEFT);

        return 'MOB'.now()->format('YmdHis').$workerSuffix;
    }

    private function formatCredential(MobileBiometricCredential $credential): array
    {
        $faceStatus = $credential->face_status;
        if ($faceStatus === MobileBiometricCredential::STATUS_APPROVED && ! $this->faceCredentialReady($credential)) {
            $faceStatus = MobileBiometricCredential::STATUS_PENDING;
        }

        return [
            'face_status' => $faceStatus,
            'face_enrolled_at' => $credential->face_enrolled_at,
            'face_approved_at' => $credential->face_approved_at,
            'local_biometric_enabled' => $credential->local_biometric_enabled,
            'local_biometric_enabled_at' => $credential->local_biometric_enabled_at,
            'failed_attempts' => $credential->failed_attempts,
            'face_threshold' => $credential->face_threshold,
            'face_similarity_threshold' => $credential->face_similarity_threshold,
            'last_face_distance' => $credential->last_face_distance,
            'last_face_similarity' => $credential->last_face_similarity,
            'blocked_until' => $credential->blocked_until,
            'last_verified_at' => $credential->last_verified_at,
        ];
    }

    private function faceCredentialReady(MobileBiometricCredential $credential): bool
    {
        return $credential->face_status === MobileBiometricCredential::STATUS_APPROVED
            && filled($credential->face_embedding);
    }

    private function formatLocalMarcacion(?ConasisLocalesMarcacion $localMarcacion): ?array
    {
        if (! $localMarcacion) {
            return null;
        }

        return [
            'id' => $localMarcacion->id,
            'local_inst_educ_id' => $localMarcacion->localInstEduc_id,
            'fecha_inicio' => $localMarcacion->fechaInicio,
            'fecha_fin' => $localMarcacion->fechaFin,
            'local' => $localMarcacion->localInstEduc?->local ? [
                'id' => $localMarcacion->localInstEduc->local->id,
                'nombre' => $localMarcacion->localInstEduc->local->nombre,
                'utm_huso' => $localMarcacion->localInstEduc->local->utm_huso,
                'utm_base' => $localMarcacion->localInstEduc->local->utm_banda,
                'utm_x_este' => $localMarcacion->localInstEduc->local->utm_x_este,
                'utm_y_norte' => $localMarcacion->localInstEduc->local->utm_y_norte,
            ] : null,
        ];
    }

    private function formatMark(ConasisMarcaciones $mark): array
    {
        return [
            'id' => $mark->id,
            'fecha_marcacion' => $mark->fechaMarcacion,
            'tipo' => $mark->tipo,
            'medio_marcacion' => $mark->medioMarcacion,
            'procesado' => (bool) $mark->procesado,
            'alta_trabajador_id' => $mark->altaTrabajador_id,
            'local_marcacion_id' => $mark->localMarcacion_id,
        ];
    }
}
