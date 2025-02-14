<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusUnidad extends Model
{
    protected $table = 'status_unidades';

    protected $primaryKey = 'idstatus_unidad';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idstatus_unidad',
        'status_unidad'
    ];
}
