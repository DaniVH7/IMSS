<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoEstablecimiento extends Model
{
    protected $table = 'tipos_establecimiento';

    protected $primaryKey = 'idtipo_establecimiento';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idtipo_establecimiento',
        'tipo_establecimiento'
    ];
}
