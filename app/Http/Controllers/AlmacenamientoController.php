<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Almacenamiento;

class AlmacenamientoController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $almacenamiento = new Almacenamiento();
        $almacenamiento->cant_almcen = Str::upper($request->cant_almcen);
        $almacenamiento->save();

        $almacenamientos = Almacenamiento::orderBy('id', 'ASC')->get();

        return [
            'type'          =>  'success',
            'title'         =>  'CORRECTO: ',
            'message'       =>  'La Categoría se guardo correctamente.',
            'almacenamientos'    =>  $almacenamientos
        ];
    }
}
