<?php

namespace App\Models;

use App\Models\Param\ParamRegimenLaboral;
use App\Models\Param\ParamTipoTrabajador;
use App\Traits\HasCodigo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CondicionesLaborales extends Model
{
    use HasCodigo, HasFactory;

    protected $table = 't_condicionesLaborales';

    public $timestamps = false;

    protected string $codigoPrefix = 'CLA';

    protected $fillable = [
        'regimenLaboral_id',
        'tipoTrabajador_id',
        'nombre',
        'abreviatura',
        'descripcion',
        'created_by',
    ];

    public function regimenLaboral(): BelongsTo
    {
        return $this->belongsTo(ParamRegimenLaboral::class, 'regimenLaboral_id');
    }

    public function tipoTrabajador(): BelongsTo
    {
        return $this->belongsTo(ParamTipoTrabajador::class, 'tipoTrabajador_id');
    }
}
