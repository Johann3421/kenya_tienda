<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Procesador;

class ProcesadorController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $procesador = new Procesador();
        $procesador->nom_pros = Str::upper($request->nom_pros);
        $procesador->save();

        $procesadores = Procesador::orderBy('id', 'ASC')->get();

        return [
            'type'          =>  'success',
            'title'         =>  'CORRECTO: ',
            'message'       =>  'La Categoría se guardo correctamente.',
            'procesadores'    =>  $procesadores
        ];
    }
}
