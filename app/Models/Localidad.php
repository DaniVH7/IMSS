<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Localidad extends Model
{
    protected $table = 'localidades';

    protected $primaryKey = 'idlocalidad';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idlocalidad',
        'localidad',
        'idestado',
        'idmunicipio'
    ];

    public function estado() {
        return $this->belongsTo(Estado::class, 'idestado', 'idestado');
    }

    public function municipio() {
        return $this->belongsTo(Municipio::class, 'idmunicipio', 'idmunicipio');
    }
}
