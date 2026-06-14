<?php

namespace App\Models;

use App\Traits\HasCodigo;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function altas(): HasMany
    {
        return $this->hasMany(AltasTrabajadores::class, 'trabajador_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'trabajador_id');
    }
}
