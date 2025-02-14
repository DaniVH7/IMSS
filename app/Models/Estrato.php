<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estrato extends Model
{
    protected $table = 'estratos';

    protected $primaryKey = 'idestrato';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idestrato',
        'estrato'
    ];
}
