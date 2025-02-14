<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subtipologia extends Model
{
    protected $table = 'subtipologias';

    protected $primaryKey = 'idsubtipologia';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idsubtipologia',
        'subtipologia',
        'descripcion_subtipologia'
    ];
}
