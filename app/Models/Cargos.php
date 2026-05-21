<?php

namespace App\Models;

use App\Models\Param\ParamRolTrabajador;
use App\Traits\HasCodigo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cargos extends Model
{
    use HasCodigo, HasFactory;

    protected $table = 't_cargos';

    public $timestamps = false;

    protected string $codigoPrefix = 'CAR';

    protected $fillable = [
        'nombre',
        'abreviatura',
        'rolTrabajador_id',
        'created_by',
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
