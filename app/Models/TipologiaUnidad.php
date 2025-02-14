<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipologiaUnidad extends Model
{
    protected $table = 'tipologias_unidades';

    protected $primaryKey = 'idtipologia_unidad';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idtipologia_unidad',
        'tipologia_unidad',
        'clave_tipologia'
    ];
}
