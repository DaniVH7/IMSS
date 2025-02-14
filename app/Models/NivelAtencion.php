<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NivelAtencion extends Model
{
    protected $table = 'niveles_atencion';

    protected $primaryKey = 'idnivel_atencion';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idnivel_atencion',
        'nivel_atencion'
    ];
}
