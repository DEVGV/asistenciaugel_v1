<?php

namespace App\Models\Conasis;

use App\Models\AltasTrabajadores;
use App\Models\Trabajador;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConasisControladoresLocales extends Model
{
    protected $table = 'conasis.t_controladoresLocales';

    public $timestamps = false;

    protected $fillable = [
        'localInstEduc_id',
        'user_id',
        'trabajador_id',
        'altaTrabajador_id',
        'fechaInicio',
        'fechaFin',
        'created_by',
    ];

    public function altaTrabajador(): BelongsTo
    {
        return $this->belongsTo(AltasTrabajadores::class, 'altaTrabajador_id');
    }

    public function localInstEduc(): BelongsTo
    {
        return $this->belongsTo(ConasisLocalesInstEduc::class, 'localInstEduc_id');
    }

    public function trabajador(): BelongsTo
    {
        return $this->belongsTo(Trabajador::class, 'trabajador_id');
    }
}
