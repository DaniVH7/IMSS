<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
    protected $table = 'municipios';

    protected $primaryKey = 'idmunicipio';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idmunicipio',
        'municipio',
        'idestado',
        'idjurisdiccion'
    ];

    public function estado() {
        return $this->belongsTo(Estado::class, 'idestado', 'idestado');
    }

    public function jurisdiccion() {
        return $this->belongsTo(Jurisdiccion::class, 'idjurisdiccion', 'idjurisdiccion');
    }
}
