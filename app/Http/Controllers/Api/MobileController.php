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
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class MobileController extends Controller
{
    private const MARKING_DISTANCE_THRESHOLD_METERS = 500.0;

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

    public function config(): JsonResponse
    {
        return response()->json([
            'data' => [
                'permission_request' => $this->mobilePermissionRequestConfig(),
            ],
        ]);
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
        $localesPorAlta = $this->activeLocalesMarcacionForAltas($trabajador, $altas->pluck('id'), $today);

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
                        $localesPorAlta[$alta->id] ?? null
                    ),
                ])->values(),
                'permission_request_url' => url('/expedientes/create'),
                'permission_request_label' => 'Solicitar Permiso',
                'permission_request' => [
                    'url' => url('/expedientes/create'),
                    'label' => 'Solicitar Permiso',
                ],
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
                'message' => $this->markingDeniedMessage($context['checks']),
                'checks' => $context['checks'],
                'schedule' => $context['schedule'],
            ], 422);
        }

        $location = $this->markingLocationValidation($context['localMarcacion'], $validated);
        if (! $location['allowed']) {
            return response()->json([
                'message' => $location['message'],
                'location' => $location,
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
                'tipo' => $context['schedule']['mark_type'] ?? 'E',
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
                'location' => $location,
                'schedule' => $context['schedule'],
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
            ->with(['altaTrabajador.institucionEducativa', 'institucionEduc'])
            ->where('trabajador_id', $trabajador->id)
            ->where('activo', true)
            ->whereDate('fechaInicio', '<=', $today)
            ->where(fn ($query) => $query->whereNull('fechaFin')->orWhereDate('fechaFin', '>=', $today))
            ->orderBy('fechaInicio')
            ->get();

        $localesPorAlta = $this->activeLocalesMarcacionForAltas(
            $trabajador,
            $horarios->pluck('altaTrabajador_id')->filter()->unique()->values(),
            $today
        );

        $detalles = ConasisDetalleHorarios::query()
            ->with([
                'horarioTrabajador.altaTrabajador.institucionEducativa',
                'horarioTrabajador.institucionEduc',
                'horarioCursoIni.curso',
                'horarioCursoIni.seccion.grado',
            ])
            ->whereIn('horarioTrabajador_id', $horarios->pluck('id'))
            ->where('aplicar', true)
            ->orderBy('nroDia')
            ->orderBy('entHoraInicio')
            ->get();

        return response()->json([
            'data' => [
                'horarios' => $horarios->map(fn (ConasisHorariosTrabajador $horario): array => array_merge([
                    'id' => $horario->id,
                    'nombre' => $horario->nombre,
                    'tipo_horario' => $horario->tipoHorario,
                    'fecha_inicio' => $horario->fechaInicio,
                    'fecha_fin' => $horario->fechaFin,
                ], $this->scheduleAssignmentPayload(
                    $horario,
                    $localesPorAlta[$horario->altaTrabajador_id] ?? null
                )))->values(),
                'detalles' => $detalles->map(function (ConasisDetalleHorarios $detalle) use ($localesPorAlta): array {
                    $horario = $detalle->horarioTrabajador;

                    return array_merge([
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
                    ], $this->scheduleAssignmentPayload(
                        $horario,
                        $horario ? ($localesPorAlta[$horario->altaTrabajador_id] ?? null) : null
                    ), $this->scheduleCoursePayload($detalle));
                })->values(),
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

    private function activeLocalesMarcacionForAltas(Trabajador $trabajador, mixed $altaIds, mixed $date)
    {
        return ConasisLocalesMarcacion::query()
            ->with('localInstEduc.local')
            ->where('trabajador_id', $trabajador->id)
            ->whereIn('altaTrabajador_id', collect($altaIds)->filter()->unique()->values())
            ->whereDate('fechaInicio', '<=', $date)
            ->where(fn ($query) => $query->whereNull('fechaFin')->orWhereDate('fechaFin', '>=', $date))
            ->orderBy('altaTrabajador_id')
            ->orderByDesc('fechaInicio')
            ->get()
            ->unique('altaTrabajador_id')
            ->keyBy('altaTrabajador_id');
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
            'horario_marcacion' => (bool) ($schedule['can_mark_now'] ?? false),
            'marca_no_duplicada' => ! (bool) ($schedule['already_marked'] ?? false),
            'biometria_facial_aprobada' => $this->faceCredentialReady($credential),
            'metodo_biometrico_habilitado' => $this->faceCredentialReady($credential) || $credential->local_biometric_enabled,
            'biometria_no_bloqueada' => ! ($credential->blocked_until && $credential->blocked_until->isFuture()),
        ];

        return [
            'allowed' => $checks['trabajador_activo']
                && $checks['alta_vigente']
                && $checks['asignado_marcacion_movil']
                && $checks['horario_marcacion']
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
            return [
                'has_active_schedule' => false,
                'has_today_schedule' => false,
                'can_mark_now' => false,
                'already_marked' => false,
                'mark_type' => null,
                'mark_label' => null,
                'active_window' => null,
                'today_windows' => [],
            ];
        }

        $today = today();
        $now = now();
        $currentDay = (int) $today->dayOfWeekIso;

        $horarios = ConasisHorariosTrabajador::query()
            ->where('trabajador_id', $trabajador->id)
            ->where('altaTrabajador_id', $alta->id)
            ->where('activo', true)
            ->whereDate('fechaInicio', '<=', $today)
            ->where(fn ($query) => $query->whereNull('fechaFin')->orWhereDate('fechaFin', '>=', $today))
            ->pluck('id');

        $details = ConasisDetalleHorarios::query()
            ->whereIn('horarioTrabajador_id', $horarios)
            ->where('aplicar', true)
            ->where('nroDia', $currentDay)
            ->orderBy('entHoraInicio')
            ->get();

        $todayMarks = ConasisMarcaciones::query()
            ->where('trabajador_id', $trabajador->id)
            ->where('altaTrabajador_id', $alta->id)
            ->where('medioMarcacion', 'M')
            ->whereIn('tipo', ['E', 'S'])
            ->whereDate('fechaMarcacion', $today)
            ->get(['tipo', 'fechaMarcacion']);

        $windows = $details
            ->flatMap(function (ConasisDetalleHorarios $detalle) use ($now, $todayMarks): array {
                return array_values(array_filter(
                    $this->scheduleShiftWindows($detalle, $now, $todayMarks)
                ));
            })
            ->sortBy('sort_time')
            ->values();
        $activeWindow = $windows->first(
            fn (array $window): bool => (bool) $window['is_current'] && ! (bool) $window['already_marked'] && (bool) $window['can_mark_now']
        );
        $currentWindow = $windows->first(fn (array $window): bool => (bool) $window['is_current']);
        $publicWindows = $windows
            ->map(function (array $window): array {
                unset($window['starts_at'], $window['ends_at'], $window['sort_time']);

                return $window;
            })
            ->values();
        $publicActiveWindow = $activeWindow;
        if ($publicActiveWindow) {
            unset($publicActiveWindow['starts_at'], $publicActiveWindow['ends_at'], $publicActiveWindow['sort_time']);
        }
        $maxTolerance = $publicWindows->max('tolerancia_minutos') ?? 0;

        return [
            'has_active_schedule' => $horarios->isNotEmpty(),
            'has_today_schedule' => $publicWindows->isNotEmpty(),
            'can_mark_now' => filled($activeWindow),
            'already_marked' => $currentWindow ? (bool) $currentWindow['already_marked'] : false,
            'mark_type' => $activeWindow['mark_type'] ?? null,
            'mark_label' => $activeWindow['mark_label'] ?? null,
            'active_window' => $publicActiveWindow,
            'start_grace_minutes' => 0,
            'end_grace_minutes' => $maxTolerance,
            'max_tolerance_minutes' => $maxTolerance,
            'today_windows' => $publicWindows,
        ];
    }

    private function scheduleShiftWindows(
        ConasisDetalleHorarios $detalle,
        CarbonInterface $now,
        mixed $todayMarks
    ): array {
        $startTime = $detalle->entHoraInicio;
        if (! filled($startTime)) {
            return [];
        }

        $endTime = $detalle->salHoraFin ?? $detalle->salHoraInicio;
        if (! filled($endTime)) {
            return [];
        }

        $shiftStart = $now->copy()->setTimeFromTimeString($startTime);
        $shiftEnd = $now->copy()->setTimeFromTimeString($endTime);

        if ($shiftEnd->lessThanOrEqualTo($shiftStart)) {
            $shiftEnd->addDay();
        }

        $isCurrent = $now->betweenIncluded($shiftStart, $shiftEnd);

        $hasMarkedE = $todayMarks->contains(function (ConasisMarcaciones $mark) use ($shiftStart, $shiftEnd): bool {
            if ($mark->tipo !== 'E' || ! $mark->fechaMarcacion) {
                return false;
            }

            return Carbon::parse($mark->fechaMarcacion)->betweenIncluded($shiftStart, $shiftEnd);
        });

        $hasMarkedS = $todayMarks->contains(function (ConasisMarcaciones $mark) use ($shiftStart, $shiftEnd): bool {
            if ($mark->tipo !== 'S' || ! $mark->fechaMarcacion) {
                return false;
            }

            return Carbon::parse($mark->fechaMarcacion)->betweenIncluded($shiftStart, $shiftEnd);
        });

        $toleranceMinutes = max(
            (int) ($detalle->entTolerancia ?? 0),
            (int) ($detalle->salTolerancia ?? 0)
        );

        $windowE = [
            'detalle_horario_id' => $detalle->id,
            'mark_type' => 'E',
            'mark_label' => 'Entrada',
            'entrada_inicio' => $detalle->entHoraInicio,
            'entrada_fin' => $detalle->entHoraFin,
            'entrada_tolerancia' => max(0, (int) ($detalle->entTolerancia ?? 0)),
            'salida_inicio' => $detalle->salHoraInicio,
            'salida_fin' => $detalle->salHoraFin,
            'salida_tolerancia' => max(0, (int) ($detalle->salTolerancia ?? 0)),
            'marcacion_inicio' => $shiftStart->format('H:i:s'),
            'marcacion_fin' => $shiftEnd->format('H:i:s'),
            'tolerancia_minutos' => $toleranceMinutes,
            'can_mark_now' => $isCurrent && ! $hasMarkedE,
            'is_current' => $isCurrent,
            'already_marked' => $hasMarkedE,
            'starts_at' => $shiftStart,
            'ends_at' => $shiftEnd,
            'sort_time' => $shiftStart->format('H:i:s').'E',
        ];

        $windowS = [
            'detalle_horario_id' => $detalle->id,
            'mark_type' => 'S',
            'mark_label' => 'Salida',
            'entrada_inicio' => $detalle->entHoraInicio,
            'entrada_fin' => $detalle->entHoraFin,
            'entrada_tolerancia' => max(0, (int) ($detalle->entTolerancia ?? 0)),
            'salida_inicio' => $detalle->salHoraInicio,
            'salida_fin' => $detalle->salHoraFin,
            'salida_tolerancia' => max(0, (int) ($detalle->salTolerancia ?? 0)),
            'marcacion_inicio' => $shiftStart->format('H:i:s'),
            'marcacion_fin' => $shiftEnd->format('H:i:s'),
            'tolerancia_minutos' => $toleranceMinutes,
            'can_mark_now' => $isCurrent && $hasMarkedE && ! $hasMarkedS,
            'is_current' => $isCurrent,
            'already_marked' => $hasMarkedS,
            'starts_at' => $shiftStart,
            'ends_at' => $shiftEnd,
            'sort_time' => $shiftStart->format('H:i:s').'S',
        ];

        return [$windowE, $windowS];
    }

    private function markingDeniedMessage(array $checks): string
    {
        if (! ($checks['alta_vigente'] ?? false)) {
            return 'No existe alta vigente para la institucion seleccionada.';
        }
        if (! ($checks['asignado_marcacion_movil'] ?? false)) {
            return 'No existe local de marcacion movil vigente para esta asignacion.';
        }
        if (! ($checks['marca_no_duplicada'] ?? true)) {
            return 'Ya existe una marca registrada para esta ventana horaria.';
        }
        if (! ($checks['horario_marcacion'] ?? false)) {
            return 'La marcacion solo esta permitida durante la ventana de entrada o salida del turno.';
        }
        if (! ($checks['metodo_biometrico_habilitado'] ?? false)) {
            return 'El docente no tiene un metodo biometrico habilitado para marcacion movil.';
        }
        if (! ($checks['biometria_no_bloqueada'] ?? false)) {
            return 'La validacion biometrica se encuentra bloqueada temporalmente.';
        }

        return 'La marcacion movil no esta habilitada para este trabajador.';
    }

    private function markingLocationValidation(?ConasisLocalesMarcacion $localMarcacion, array $data): array
    {
        $local = $localMarcacion?->localInstEduc?->local;
        if (! $local) {
            return [
                'allowed' => false,
                'message' => 'No existe local de marcacion con coordenadas para validar ubicacion.',
            ];
        }

        foreach (['utm_huso', 'utm_base', 'utm_x_este', 'utm_y_norte'] as $field) {
            if (! filled($data[$field] ?? null)) {
                return [
                    'allowed' => false,
                    'message' => 'Debe activar el GPS y permitir la ubicacion antes de marcar.',
                ];
            }
        }

        if (! filled($local->utm_huso) || ! filled($local->utm_banda)
            || ! filled($local->utm_x_este) || ! filled($local->utm_y_norte)) {
            return [
                'allowed' => false,
                'message' => 'El local de marcacion no tiene coordenadas UTM completas.',
            ];
        }

        $mobileZone = (int) $data['utm_huso'];
        $localZone = (int) $local->utm_huso;
        $mobileBand = strtoupper(trim((string) $data['utm_base']));
        $localBand = strtoupper(trim((string) $local->utm_banda));

        if ($mobileZone !== $localZone || $mobileBand !== $localBand) {
            return [
                'allowed' => false,
                'message' => 'La ubicacion enviada no corresponde a la zona UTM del local.',
                'mobile_zone' => $mobileZone,
                'mobile_band' => $mobileBand,
                'local_zone' => $localZone,
                'local_band' => $localBand,
            ];
        }

        $dx = (float) $data['utm_x_este'] - (float) $local->utm_x_este;
        $dy = (float) $data['utm_y_norte'] - (float) $local->utm_y_norte;
        $distance = round(sqrt(($dx * $dx) + ($dy * $dy)), 2);

        return [
            'allowed' => $distance <= self::MARKING_DISTANCE_THRESHOLD_METERS,
            'message' => $distance <= self::MARKING_DISTANCE_THRESHOLD_METERS
                ? 'Ubicacion validada dentro del local.'
                : sprintf(
                    'Estas a %.2f metros del local. El maximo permitido es %.0f metros.',
                    $distance,
                    self::MARKING_DISTANCE_THRESHOLD_METERS
                ),
            'distance_meters' => $distance,
            'threshold_meters' => self::MARKING_DISTANCE_THRESHOLD_METERS,
            'mobile_utm' => [
                'huso' => $mobileZone,
                'base' => $mobileBand,
                'x_este' => round((float) $data['utm_x_este'], 2),
                'y_norte' => round((float) $data['utm_y_norte'], 2),
            ],
            'local_utm' => [
                'huso' => $localZone,
                'base' => $localBand,
                'x_este' => round((float) $local->utm_x_este, 2),
                'y_norte' => round((float) $local->utm_y_norte, 2),
            ],
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

    private function mobilePermissionRequestConfig(): array
    {
        return [
            'label' => trim((string) config('mobile.permission_request.label', 'Solicitar Permiso')) ?: 'Solicitar Permiso',
            'url' => trim((string) config('mobile.permission_request.url', '')),
        ];
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

    private function scheduleAssignmentPayload(
        ?ConasisHorariosTrabajador $horario,
        ?ConasisLocalesMarcacion $localMarcacion
    ): array {
        if (! $horario) {
            return [
                'alta_trabajador_id' => null,
                'institucion_educativa_id' => null,
                'institucion_nombre' => null,
                'local_marcacion_id' => null,
                'local_inst_educ_id' => null,
                'local_id' => null,
                'local_nombre' => null,
                'local_marcacion' => null,
            ];
        }

        $alta = $horario->altaTrabajador;
        $institucion = $horario->institucionEduc ?? $alta?->institucionEducativa;
        $local = $localMarcacion?->localInstEduc?->local;

        return [
            'alta_trabajador_id' => $horario->altaTrabajador_id,
            'institucion_educativa_id' => $horario->institucionEduc_id ?? $alta?->institucionEducativa_id,
            'institucion_nombre' => $institucion?->nombreLegal,
            'local_marcacion_id' => $localMarcacion?->id,
            'local_inst_educ_id' => $localMarcacion?->localInstEduc_id,
            'local_id' => $local?->id,
            'local_nombre' => $local?->nombre,
            'local_marcacion' => $this->formatLocalMarcacion($localMarcacion),
        ];
    }

    private function scheduleCoursePayload(ConasisDetalleHorarios $detalle): array
    {
        $horarioCurso = $detalle->horarioCursoIni;
        $curso = $horarioCurso?->curso;
        $seccion = $horarioCurso?->seccion;
        $grado = $seccion?->grado;
        $cursoNombre = $curso?->nombre ?? $detalle->nombreTurno;

        return [
            'horario_curso_id' => $horarioCurso?->id,
            'curso_id' => $curso?->id,
            'curso_nombre' => $cursoNombre,
            'nombre_curso' => $cursoNombre,
            'seccion_id' => $seccion?->id,
            'seccion_nombre' => $seccion?->nombre,
            'grado_id' => $grado?->id,
            'grado_nombre' => $grado?->nombre,
            'horario_curso' => $horarioCurso ? [
                'id' => $horarioCurso->id,
                'curso' => $curso ? [
                    'id' => $curso->id,
                    'nombre' => $curso->nombre,
                    'sigla' => $curso->sigla,
                ] : null,
                'seccion' => $seccion ? [
                    'id' => $seccion->id,
                    'nombre' => $seccion->nombre,
                    'sigla' => $seccion->sigla,
                    'grado' => $grado ? [
                        'id' => $grado->id,
                        'nombre' => $grado->nombre,
                        'sigla' => $grado->sigla,
                    ] : null,
                ] : null,
            ] : null,
        ];
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
            'tipo_label' => $mark->tipo === 'S' ? 'Salida' : 'Entrada',
            'medio_marcacion' => $mark->medioMarcacion,
            'procesado' => (bool) $mark->procesado,
            'alta_trabajador_id' => $mark->altaTrabajador_id,
            'local_marcacion_id' => $mark->localMarcacion_id,
        ];
    }
}
