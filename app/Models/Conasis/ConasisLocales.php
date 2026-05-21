<?php

namespace App\Models\Conasis;

use App\Models\Zonas;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConasisLocales extends Model
{
    protected $table = 'conasis.t_locales';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'domicilio',
        'zona_id',
        'ubigeo',
        'utm_huso',
        'utm_banda',
        'utm_x_este',
        'utm_y_norte',
        'created_by',
        'activo',
    ];

    public function zona(): BelongsTo
    {
        return $this->belongsTo(Zonas::class, 'zona_id');
    }
}
