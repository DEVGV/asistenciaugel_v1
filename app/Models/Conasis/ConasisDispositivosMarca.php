<?php

namespace App\Models\Conasis;

use App\Models\Telefonos;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConasisDispositivosMarca extends Model
{
    protected $table = 'conasis.t_dispositivosMarca';

    public $timestamps = false;

    protected $fillable = [
        'telefonoMovil_id',
        'fechaInicio',
        'fechaFin',
        'created_by',
    ];

    public function telefonoMovil(): BelongsTo
    {
        return $this->belongsTo(Telefonos::class, 'telefonoMovil_id');
    }
}
