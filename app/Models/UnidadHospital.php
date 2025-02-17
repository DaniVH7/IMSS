<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnidadHospital extends Model
{
    protected $table = 'unidades_hospitales';

    protected $primaryKey = 'clues';

    public $incrementing = false;

    public $timestamps = true;

    protected $fillable = [
        'idestado',
        'idmunicipio',
        'idlocalidad',
        'clues',
        'idtipo_unidad',
        'idtipologia_unidad',
        'idinstitucion',
        'idestrato',
        'idjurisdiccion',
        'idtipo_vialidad',
        'idtipo_asentamiento',
        'idtipo_administracion',
        'idstatus_unidad',
        'idmotivo_baja',
        'idtipo_establecimiento',
        'idsubtipologia',
        'idnivel_atencion',
        'nombre',
        'vialidad',
        'asentamiento',
        'nointerior',
        'noexterior',
        'cp',
        'latitud',
        'longitud',
        'email',
        'telefono',
        'construccion',
        'inicio_operacion',
        'horarios',
        'nombre_responsable',
        'pa_responsable',
        'sa_responsable',
        'cedula_responsable',
        'alineacion'
    ];

    // Relaciones
    public function estado() {
        return $this->belongsTo(Estado::class, 'idestado', 'idestado');
    }

    public function municipio() {
        return $this->belongsTo(Municipio::class, 'idmunicipio', 'idmunicipio');
    }

    public function localidad() {
        return $this->belongsTo(Localidad::class, ['idestado', 'idmunicipio', 'idlocalidad'], ['idestado', 'idmunicipio', 'idlocalidad']);
    }

    public function jurisdiccion() {
        return $this->belongsTo(Jurisdiccion::class, 'idjurisdiccion', 'idjurisdiccion');
    }

    public function institucion() {
        return $this->belongsTo(Institucion::class, 'idinstitucion', 'idinstitucion');
    }

    public function estrato() {
        return $this->belongsTo(Estrato::class, 'idestrato', 'idestrato');
    }

    public function tipoUnidad() {
        return $this->belongsTo(TipoUnidad::class, 'idtipo_unidad', 'idtipo_unidad');
    }

    public function tipologiaUnidad() {
        return $this->belongsTo(TipologiaUnidad::class, 'idtipologia_unidad', 'idtipologia_unidad');
    }

    public function tipoVialidad() {
        return $this->belongsTo(TipoVialidad::class, 'idtipo_vialidad', 'idtipo_vialidad');
    }

    public function tipoAsentamiento() {
        return $this->belongsTo(TipoAsentamiento::class, 'idtipo_asentamiento', 'idtipo_asentamiento');
    }

    public function tipoAdministracion() {
        return $this->belongsTo(TipoAdministracion::class, 'idtipo_administracion', 'idtipo_administracion');
    }

    public function statusUnidad() {
        return $this->belongsTo(StatusUnidad::class, 'idstatus_unidad', 'idstatus_unidad');
    }

    public function motivoBaja() {
        return $this->belongsTo(MotivoBaja::class, 'idmotivo_baja', 'idmotivo_baja');
    }

    public function tipoEstablecimiento() {
        return $this->belongsTo(TipoEstablecimiento::class, 'idtipo_establecimiento', 'idtipo_establecimiento');
    }

    public function subtipologia() {
        return $this->belongsTo(Subtipologia::class, 'idsubtipologia', 'idsubtipologia');
    }

    public function nivelAtencion() {
        return $this->belongsTo(NivelAtencion::class, 'idnivel_atencion', 'idnivel_atencion');
    }
}
