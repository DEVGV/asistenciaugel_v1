<?php

namespace App\Models\Conasis;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConasisRelojes extends Model
{
    protected $table = 'conasis.t_relojes';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'dreccionIP',
        'direccionMac',
        'puerto',
        'serie',
        'localInstEduc_id',
        'idBiometrico',
        'created_by',
        'activo',
    ];

    public function localInstEduc(): BelongsTo
    {
        return $this->belongsTo(ConasisLocalesInstEduc::class, 'localInstEduc_id');
    }
}
