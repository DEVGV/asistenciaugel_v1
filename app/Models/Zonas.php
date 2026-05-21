<?php

namespace App\Models;

use App\Models\Param\ParamDistritos;
use App\Models\Param\ParamTiposZona;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Zonas extends Model
{
    use HasFactory;

    protected $table = 't_zonas';

    public $timestamps = false;

    protected $fillable = [
        'tipoZona_id',
        'distrito_id',
        'nombre',
        'abreviatura',
        'created_by',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function distrito(): BelongsTo
    {
        return $this->belongsTo(ParamDistritos::class, 'distrito_id');
    }

    public function tipoZona(): BelongsTo
    {
        return $this->belongsTo(ParamTiposZona::class, 'tipoZona_id');
    }
}
