<?php

namespace App\Models\Param;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParamMotivosSuspLab extends Model
{
    protected $table = 'param.t00_motivosSuspLab';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'tipoSuspensionLaboral_id',
        'descripcion',
        'abreviatura',
        'abreviaturaPers',
        'conGoceHaber',
        'asusfal',
        'diasMaxCiclo',
        'codigoProg',
        'autorizadoPor',
        'created_by',
        'activo',
    ];

    public function tipoSuspensionLaboral(): BelongsTo
    {
        return $this->belongsTo(ParamTipoSuspensionLaboral::class, 'tipoSuspensionLaboral_id');
    }
}
