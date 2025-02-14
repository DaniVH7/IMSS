<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institucion extends Model
{
    protected $table = 'instituciones';

    protected $primaryKey = 'idinstitucion';
    
    public $incrementing = false;
    
    public $timestamps = false;

    protected $fillable = [
        'idinstitucion',
        'institucion',
        'descripcion_corta',
        'iniciales'
    ];
}
