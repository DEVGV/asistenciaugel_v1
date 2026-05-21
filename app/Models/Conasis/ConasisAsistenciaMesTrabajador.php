<?php

namespace App\Models\Conasis;

use App\Models\Param\ParamTurnos;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConasisAsistenciaMesTrabajador extends Model
{
    protected $table = 'conasis.t_asistenciaMesTrabajador';

    public $timestamps = false;

    protected $fillable = [
        'asistencia_id',
        'localInstEduc_id',
        'turno_id',
        'nroTurno',
        'created_by',
        'e1',
        's1',
        'c1',
        'e2',
        's2',
        'c2',
        'e3',
        's3',
        'c3',
        'e4',
        's4',
        'c4',
        'e5',
        's5',
        'c5',
        'e6',
        's6',
        'c6',
        'e7',
        's7',
        'c7',
        'e8',
        's8',
        'c8',
        'e9',
        's9',
        'c9',
        'e10',
        's10',
        'c10',
        'e11',
        's11',
        'c11',
        'e12',
        's12',
        'c12',
        'e13',
        's13',
        'c13',
        'e14',
        's14',
        'c14',
        'e15',
        's15',
        'c15',
        'e16',
        's16',
        'c16',
        'e17',
        's17',
        'c17',
        'e18',
        's18',
        'c18',
        'e19',
        's19',
        'c19',
        'e20',
        's20',
        'c20',
        'e21',
        's21',
        'c21',
        'e22',
        's22',
        'c22',
        'e23',
        's23',
        'c23',
        'e24',
        's24',
        'c24',
        'e25',
        's25',
        'c25',
        'e26',
        's26',
        'c26',
        'e27',
        's27',
        'c27',
        'e28',
        's28',
        'c28',
        'e29',
        's29',
        'c29',
        'e30',
        's30',
        'c30',
        'e31',
        's31',
        'c31',
    ];

    public function turno(): BelongsTo
    {
        return $this->belongsTo(ParamTurnos::class, 'turno_id');
    }

    public function asistencia(): BelongsTo
    {
        return $this->belongsTo(ConasisAsistencia::class, 'asistencia_id');
    }

    public function localInstEduc(): BelongsTo
    {
        return $this->belongsTo(ConasisLocalesInstEduc::class, 'localInstEduc_id');
    }
}
