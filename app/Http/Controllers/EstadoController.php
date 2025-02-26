<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estado;

class EstadoController extends Controller
{
    public function index()
    {
        $estados = Estado::all(); // Obtiene todos los estados
        return view('estados.index', compact('estados'));
    }
}
?>