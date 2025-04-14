<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Ram;

class RamController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $ram = new Ram();
        $ram->nom_ram = Str::upper($request->nom_ram);
        $ram->save();

        $rams = Ram::orderBy('id', 'ASC')->get();

        return [
            'type'          =>  'success',
            'title'         =>  'CORRECTO: ',
            'message'       =>  'La Categoría se guardo correctamente.',
            'rams'    =>  $rams
        ];
    }
}
