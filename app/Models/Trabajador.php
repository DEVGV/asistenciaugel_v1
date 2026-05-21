<?php

namespace App\Models;

use App\Traits\HasCodigo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trabajador extends Model
{
    use HasCodigo;

    protected $table = 't_trabajador';

    public $timestamps = false;

    protected string $codigoPrefix = 'TRA';

    protected $fillable = [
        'persona_id',
        'created_by',
        'activo',
    ];

    public function persona(): BelongsTo
    {
        return $this->belongsTo(Personas::class, 'persona_id');
    }
}
