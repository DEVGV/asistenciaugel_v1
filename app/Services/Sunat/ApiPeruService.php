<?php

namespace App\Services\Sunat;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiPeruService
{
    private string $baseUrl;

    private string $token;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.apiperu.url'), '/');
        $this->token = (string) config('services.apiperu.token');
    }

    /**
     * Consulta datos de una empresa por RUC (11 dígitos).
     *
     * @return array{
     *     success: bool,
     *     data?: array{
     *         ruc: string,
     *         nombre_o_razon_social: string,
     *         estado: string,
     *         condicion: string,
     *         direccion: string,
     *         direccion_completa: string,
     *         departamento: string,
     *         provincia: string,
     *         distrito: string,
     *         ubigeo_sunat: string,
     *         ubigeo: array<int, string>,
     *         es_agente_de_retencion: string,
     *         es_agente_de_percepcion: string,
     *         es_buen_contribuyente: string,
     *     },
     *     error?: string
     * }
     */
    public function consultarRuc(string $ruc): array
    {
        try {
            $response = Http::withToken($this->token)
                ->withoutVerifying()
                ->post("{$this->baseUrl}/ruc", ['ruc' => $ruc]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning('ApiPeru RUC lookup failed', [
                'ruc' => $ruc,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'error' => 'No se encontraron datos para el RUC proporcionado.',
            ];
        } catch (ConnectionException $e) {
            Log::error('ApiPeru RUC connection error', ['ruc' => $ruc, 'error' => $e->getMessage()]);

            return [
                'success' => false,
                'error' => 'Error de conexión con el servicio de consulta RUC.',
            ];
        }
    }

    /**
     * Consulta datos de una persona por DNI (8 dígitos).
     *
     * @return array{
     *     success: bool,
     *     data?: array{
     *         numero: string,
     *         nombre_completo: string,
     *         nombres: string,
     *         apellido_paterno: string,
     *         apellido_materno: string,
     *         codigo_verificacion: string,
     *     },
     *     error?: string
     * }
     */
    public function consultarDni(string $dni): array
    {
        try {
            $response = Http::withToken($this->token)
                ->withoutVerifying()
                ->post("{$this->baseUrl}/dni", ['dni' => $dni]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::warning('ApiPeru DNI lookup failed', [
                'dni' => $dni,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return [
                'success' => false,
                'error' => 'No se encontraron datos para el DNI proporcionado.',
            ];
        } catch (ConnectionException $e) {
            Log::error('ApiPeru DNI connection error', ['dni' => $dni, 'error' => $e->getMessage()]);

            return [
                'success' => false,
                'error' => 'Error de conexión con el servicio de consulta DNI.',
            ];
        }
    }
}
