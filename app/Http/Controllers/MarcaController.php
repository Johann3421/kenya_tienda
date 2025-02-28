<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Models\Marca;

class MarcaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function store(Request $request)
    {
        $marca = new Marca();
        $marca->nombre = Str::upper($request->nombre);
        $marca->activo = 'SI';
        $marca->save();

        $marcas = Marca::where('activo', 'SI')->orderBy('nombre', 'ASC')->get();

        return [
            'type'          =>  'success',
            'title'         =>  'CORRECTO: ',
            'message'       =>  'La Marca se guardo correctamente.',
            'marcas'    =>  $marcas
        ];
    }
}
