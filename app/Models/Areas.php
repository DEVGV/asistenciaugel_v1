<?php

namespace App\Models;

use App\Models\Param\ParamRolTrabajador;
use App\Traits\HasCodigo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Areas extends Model
{
    use HasCodigo, HasFactory;

    protected $table = 't_areas';

    public $timestamps = false;

    protected string $codigoPrefix = 'ARE';

    protected $fillable = [
        'nombre',
        'sigla',
        'descripcion',
        'rolTrabajador_id',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function rolTrabajador(): BelongsTo
    {
        return $this->belongsTo(ParamRolTrabajador::class, 'rolTrabajador_id');
    }
}
