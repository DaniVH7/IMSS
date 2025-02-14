<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MotivosBaja extends Model
{
    protected $table = 'motivos_baja';

    protected $primaryKey = 'idmotivo_baja';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idmotivo_baja',
        'motivo_baja'
    ];
}
