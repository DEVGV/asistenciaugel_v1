<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Emails extends Model
{
    protected $table = 't_emails';

    public $timestamps = false;

    protected $fillable = [
        'persona_id',
        'entidad_id',
        'institucionEduc_id',
        'email',
        'personalInst',
        'fechaInicio',
        'fechaFin',
        'created_by',
    ];

    public function entidad(): BelongsTo
    {
        return $this->belongsTo(Entidades::class, 'entidad_id');
    }

    public function institucionEduc(): BelongsTo
    {
        return $this->belongsTo(InstitucionesEduc::class, 'institucionEduc_id');
    }

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Personas::class, 'persona_id');
    }
}
