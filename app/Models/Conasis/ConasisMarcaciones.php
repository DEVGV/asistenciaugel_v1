<?php

namespace App\Models\Conasis;

use App\Models\AltasTrabajadores;
use App\Models\Trabajador;
use App\Traits\HasCodigo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConasisMarcaciones extends Model
{
    use HasCodigo;

    protected $table = 'conasis.t_marcaciones';

    public $timestamps = false;

    protected string $codigoPrefix = 'MAR';

    protected $fillable = [
        'trabajador_id',
        'altaTrabajador_id',
        'localMarcacion_id',
        'codigo',
        'fechaMarcacion',
        'fechaRegistro',
        'reloj_id',
        'tipo',
        'medioMarcacion',
        'procesado',
        'dispositivoMarca_id',
        'utm_huso',
        'utm_base',
        'utm_x_este',
        'utm_y_norte',
        'created_by',
    ];

    public function altaTrabajador(): BelongsTo
    {
        return $this->belongsTo(AltasTrabajadores::class, 'altaTrabajador_id');
    }

    public function dispositivoMarca(): BelongsTo
    {
        return $this->belongsTo(ConasisDispositivosMarca::class, 'dispositivoMarca_id');
    }

    public function localMarcacion(): BelongsTo
    {
        return $this->belongsTo(ConasisLocalesMarcacion::class, 'localMarcacion_id');
    }

    public function reloj(): BelongsTo
    {
        return $this->belongsTo(ConasisRelojes::class, 'reloj_id');
    }

    public function trabajador(): BelongsTo
    {
        return $this->belongsTo(Trabajador::class, 'trabajador_id');
    }
}
