<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoVialidad extends Model
{
    protected $table = 'tipos_vialidades';

    protected $primaryKey = 'idtipo_vialidad';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idtipo_vialidad',
        'tipo_vialidad'
    ];
}
