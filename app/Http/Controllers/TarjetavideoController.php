<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Tarjetavideo;

class TarjetavideoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
    
        try {
            $tarjetavideo = new Tarjetavideo();
        $tarjetavideo->tarjetavideo = Str::upper($request->tarjetavideo);
        $tarjetavideo->save();

        $tarjetavideos = Tarjetavideo::orderBy('id', 'ASC')->get();

        return [
            'type'          =>  'success',
            'title'         =>  'CORRECTO: ',
            'message'       =>  'La Categoría se guardo correctamente.',
            'tarjetavideos'    =>  $tarjetavideos
        ];
        } catch (\Exception $e) {
            return [
                'type'    => 'error',
                'message' => 'Ocurrió un error: ' . $e->getMessage(),
            ];
        }

    }
}
