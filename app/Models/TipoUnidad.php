<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoUnidad extends Model
{
    protected $table = 'tipos_unidades';

    protected $primaryKey = 'idtipo_unidad';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idtipo_unidad',
        'tipo_unidad'
    ];
}
