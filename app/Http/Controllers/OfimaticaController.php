<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Ofimatica;

class OfimaticaController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $ofimatica = new Ofimatica();
        $ofimatica->ofimatica = Str::upper($request->ofimatica);
        $ofimatica->save();

        $ofimaticas = Ofimatica::orderBy('id', 'ASC')->get();

        return [
            'type'          =>  'success',
            'title'         =>  'CORRECTO: ',
            'message'       =>  'La Categoría se guardo correctamente.',
            'ofimaticas'    =>  $ofimaticas
        ];
    }
}
