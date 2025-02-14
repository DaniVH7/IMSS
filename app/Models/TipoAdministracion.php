<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoAdministracion extends Model
{
    protected $table = 'tipos_administracion';

    protected $primaryKey = 'idtipo_administracion';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idtipo_administracion',
        'tipo_administracion'
    ];
}
