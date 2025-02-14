<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoAsentamiento extends Model
{
    protected $table = 'tipos_asentamientos';

    protected $primaryKey = 'idtipo_asentamiento';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idtipo_asentamiento',
        'tipo_asentamiento'
    ];
}
