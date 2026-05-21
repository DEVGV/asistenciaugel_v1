<?php

namespace App\Models\Conasis;

use App\Models\Param\ParamDocumentos;
use App\Models\Trabajador;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConasisDocumentosTram extends Model
{
    protected $table = 'conasis.t_documentosTram';

    public $timestamps = false;

    protected $fillable = [
        'expediente_id',
        'documento_id',
        'nroDoc',
        'fechaDoc',
        'trabajadorDoc_id',
        'rutaDoc',
        'observacion',
        'created_by',
    ];

    public function documento(): BelongsTo
    {
        return $this->belongsTo(ParamDocumentos::class, 'documento_id');
    }

    public function expediente(): BelongsTo
    {
        return $this->belongsTo(ConasisExpediente::class, 'expediente_id');
    }

    public function trabajadorDoc(): BelongsTo
    {
        return $this->belongsTo(Trabajador::class, 'trabajadorDoc_id');
    }
}
