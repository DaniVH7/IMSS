<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jurisdiccion extends Model
{
    protected $table = 'jurisdicciones';

    protected $primaryKey = 'idjurisdiccion';

    public $incrementing = false;

    public $timestamps = false;

    protected $fillable = [
        'idjurisdiccion',
        'idestado',
        'jurisdiccion'
    ];

    public function estado() {
        return $this->belongsTo(Estado::class, 'idestado', 'idestado');
    }
}
