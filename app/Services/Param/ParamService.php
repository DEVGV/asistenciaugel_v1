<?php

namespace App\Services\Param;

use App\Models\Param\ParamDepartamento;
use App\Models\Param\ParamDistritos;
use App\Models\Param\ParamDocumentos;
use App\Models\Param\ParamEstadosAsis;
use App\Models\Param\ParamEstadosTram;
use App\Models\Param\ParamFeriados;
use App\Models\Param\ParamModalidadesForm;
use App\Models\Param\ParamMotivoDeBajas;
use App\Models\Param\ParamMotivosSuspLab;
use App\Models\Param\ParamNivelesCiclo;
use App\Models\Param\ParamOperadores;
use App\Models\Param\ParamPais;
use App\Models\Param\ParamProvincias;
use App\Models\Param\ParamRegimenEduc;
use App\Models\Param\ParamRegimenLaboral;
use App\Models\Param\ParamRolTrabajador;
use App\Models\Param\ParamSexos;
use App\Models\Param\ParamSituacionLaboral;
use App\Models\Param\ParamTipoContrato;
use App\Models\Param\ParamTipoDocIdentidad;
use App\Models\Param\ParamTipoEntidad;
use App\Models\Param\ParamTipoInstEduc;
use App\Models\Param\ParamTipoSuspensionLaboral;
use App\Models\Param\ParamTiposZona;
use App\Models\Param\ParamTipoTrabajador;
use App\Models\Param\ParamTurnos;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class ParamService
{
    /**
     * Mapeo de slug URL → clase del modelo Param.
     *
     * @var array<string, class-string<Model>>
     */
    private const MODEL_MAP = [
        'tipos-doc-identidad' => ParamTipoDocIdentidad::class,
        'sexos' => ParamSexos::class,
        'paises' => ParamPais::class,
        'departamentos' => ParamDepartamento::class,
        'provincias' => ParamProvincias::class,
        'distritos' => ParamDistritos::class,
        'tipos-zona' => ParamTiposZona::class,
        'regimenes-laborales' => ParamRegimenLaboral::class,
        'tipos-trabajador' => ParamTipoTrabajador::class,
        'tipos-contrato' => ParamTipoContrato::class,
        'situaciones-laborales' => ParamSituacionLaboral::class,
        'motivos-baja' => ParamMotivoDeBajas::class,
        'roles-trabajador' => ParamRolTrabajador::class,
        'turnos' => ParamTurnos::class,
        'estados-asistencia' => ParamEstadosAsis::class,
        'estados-tramite' => ParamEstadosTram::class,
        'motivos-susp-lab' => ParamMotivosSuspLab::class,
        'feriados' => ParamFeriados::class,
        'operadores' => ParamOperadores::class,
        'documentos' => ParamDocumentos::class,
        'regimen-educ' => ParamRegimenEduc::class,
        'tipo-inst-educ' => ParamTipoInstEduc::class,
        'modalidades-form' => ParamModalidadesForm::class,
        'niveles-ciclo' => ParamNivelesCiclo::class,
        'tipo-susp-laboral' => ParamTipoSuspensionLaboral::class,
        'tipo-entidad' => ParamTipoEntidad::class,
    ];

    /**
     * Tablas que requieren filtro jerárquico (ubigeo).
     *
     * @var array<string, string>
     */
    private const HIERARCHICAL_FILTERS = [
        'departamentos' => 'pais_id',
        'provincias' => 'departamento_id',
        'distritos' => 'provincia_id',
    ];

    /**
     * Obtiene los datos de un tipo de parámetro.
     *
     * @return Collection<int, Model>
     */
    public function obtenerPorTipo(string $tipo, ?int $parentId = null): Collection
    {
        $modelClass = $this->resolverModelo($tipo);

        if ($modelClass === null) {
            return collect();
        }

        $cacheKey = "param.{$tipo}".($parentId !== null ? ".{$parentId}" : '');

        $cached = Cache::remember($cacheKey, now()->addHour(), function () use ($tipo, $modelClass, $parentId) {
            $query = $modelClass::query();
            $model = new $modelClass;

            if ($parentId !== null && isset(self::HIERARCHICAL_FILTERS[$tipo])) {
                $query->where(self::HIERARCHICAL_FILTERS[$tipo], $parentId);
            }

            if (in_array('activo', $model->getFillable(), true)) {
                $query->where('activo', true);
            }

            $orderColumn = $this->resolverColumnaOrden($model);

            return $query->orderBy($orderColumn)->get()->toArray();
        });

        return collect($cached);
    }

    /**
     * Obtiene los tipos de parámetros disponibles.
     *
     * @return array<int, string>
     */
    public function obtenerTiposDisponibles(): array
    {
        return array_keys(self::MODEL_MAP);
    }

    /**
     * Verifica si un tipo de parámetro existe.
     */
    public function existeTipo(string $tipo): bool
    {
        return isset(self::MODEL_MAP[$tipo]);
    }

    /**
     * Resuelve la columna de ordenamiento para un modelo.
     * Prioriza: nombre > descripcion > id.
     */
    private function resolverColumnaOrden(Model $model): string
    {
        $fillable = $model->getFillable();

        if (in_array('nombre', $fillable, true)) {
            return 'nombre';
        }

        if (in_array('descripcion', $fillable, true)) {
            return 'descripcion';
        }

        return 'id';
    }

    /**
     * Resuelve la clase del modelo para un tipo dado.
     *
     * @return class-string<Model>|null
     */
    private function resolverModelo(string $tipo): ?string
    {
        return self::MODEL_MAP[$tipo] ?? null;
    }
}
