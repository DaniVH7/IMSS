<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $table = 'estados';

    protected $primaryKey = 'idestado';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idestado',
        'estado',
        'siglas'
    ];

    public function jurisdicciones() {
        return $this->hasMany(Jurisdiccion::class, 'idestado', 'idestado');
    }
}
