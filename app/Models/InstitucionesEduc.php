<?php

namespace App\Models;

use App\Models\Conasis\ConasisDiasNoLaborables;
use App\Models\Conasis\ConasisLocalesInstEduc;
use App\Models\Param\ParamModalidadesForm;
use App\Models\Param\ParamNivelesCiclo;
use App\Models\Param\ParamRegimenEduc;
use App\Models\Param\ParamTipoInstEduc;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InstitucionesEduc extends Model
{
    protected $table = 't_institucionesEduc';

    public $timestamps = false;

    protected $fillable = [
        'entidadUgel_id',
        'entidadAdmin_id',
        'codigoInstitucion',
        'codigoModular',
        'nombreLegal',
        'regimenEduc_id',
        'tipoInstEduc_id',
        'modalidadFormativa_id',
        'nivelCiclo_id',
        'fechaInicio',
        'fechaFin',
        'created_by',
    ];

    public function modalidadFormativa(): BelongsTo
    {
        return $this->belongsTo(ParamModalidadesForm::class, 'modalidadFormativa_id');
    }

    public function nivelCiclo(): BelongsTo
    {
        return $this->belongsTo(ParamNivelesCiclo::class, 'nivelCiclo_id');
    }

    public function regimenEduc(): BelongsTo
    {
        return $this->belongsTo(ParamRegimenEduc::class, 'regimenEduc_id');
    }

    public function tipoInstEduc(): BelongsTo
    {
        return $this->belongsTo(ParamTipoInstEduc::class, 'tipoInstEduc_id');
    }

    public function entidadUgel(): BelongsTo
    {
        return $this->belongsTo(Entidades::class, 'entidadUgel_id');
    }

    public function entidadAdmin(): BelongsTo
    {
        return $this->belongsTo(Entidades::class, 'entidadAdmin_id');
    }

    public function cursos(): HasMany
    {
        return $this->hasMany(CursosIE::class, 'institucionEduc_id');
    }

    public function grados(): HasMany
    {
        return $this->hasMany(GradosIE::class, 'institucionEduc_id');
    }

    public function localesInstEduc(): HasMany
    {
        return $this->hasMany(ConasisLocalesInstEduc::class, 'institucionEduc_id');
    }

    public function diasNoLaborables(): HasMany
    {
        return $this->hasMany(ConasisDiasNoLaborables::class, 'institucionEduc_id');
    }

    public function telefonos(): HasMany
    {
        return $this->hasMany(Telefonos::class, 'institucionEduc_id');
    }

    public function emails(): HasMany
    {
        return $this->hasMany(Emails::class, 'institucionEduc_id');
    }

    public function domicilios(): HasMany
    {
        return $this->hasMany(Domicilios::class, 'institucionEduc_id');
    }
}
