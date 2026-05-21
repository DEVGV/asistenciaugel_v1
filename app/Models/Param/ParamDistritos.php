<?php

namespace App\Models\Param;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ParamDistritos extends Model
{
    protected $table = 'param.t28_distritos';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'abreviatura',
        'provincia_id',
        'activo',
    ];

    public function provincia(): BelongsTo
    {
        return $this->belongsTo(ParamProvincias::class, 'provincia_id');
    }
}
