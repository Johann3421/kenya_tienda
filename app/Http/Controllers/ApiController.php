<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Api;
use App\Models\Cliente;
use App\Models\Proveedor;
use Auth;
use DB;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('sistema.configuracion.apis');
    }

    public function buscar(Request $request)
    {
        $apis = Api::where('descripcion', 'LIKE', "%{$request->search}%")->paginate(10);

        return [
            'pagination' => [
                'total' => $apis->total(),
                'current_page' => $apis->currentPage(),
                'per_page' => $apis->perPage(),
                'last_page' => $apis->lastPage(),
                'from' => $apis->firstItem(),
                'to' => $apis->lastPage(),
                'index' => ($apis->currentPage()-1)*$apis->perPage(),
            ],
            'apis' => $apis,
        ];
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'descripcion'   => 'required',
            'host'          => 'required',
            'url'           => 'required',
            'token'         => 'required',
        ]);

        try {

            DB::beginTransaction();

            $api = new Api();
            $api->descripcion = strtoupper($request->descripcion);
            $api->host = $request->host;
            $api->url = $request->url;
            $api->token = $request->token;
            $api->observacion = strtoupper($request->observacion);
            $api->activo = 'SI';
            $api->save();
            
            DB::commit();

            return [
                'state'     =>  'alert-success',
                'message'   =>  'El Api para '.strtoupper($request->descripcion).' se guardo correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'state'     =>  'alert-danger',
                'message'   =>  'Ocurrio un error al guardar el Api, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'descripcion'   => 'required',
            'host'          => 'required',
            'url'           => 'required',
            'token'         => 'required',
            'activo'        => 'required',
        ]);

        try {

            DB::beginTransaction();

            $api = Api::findOrFail($request->id);
            $api->descripcion = strtoupper($request->descripcion);
            $api->host = $request->host;
            $api->url = $request->url;
            $api->token = $request->token;
            $api->observacion = strtoupper($request->observacion);
            $api->activo = $request->activo;
            $api->save();
            
            DB::commit();

            return [
                'state'     =>  'alert-success',
                'message'   =>  'El Api para '.strtoupper($request->descripcion).' se actualizo correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'state'     =>  'alert-danger',
                'message'   =>  'Ocurrio un error al actualizar el Api, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

    public function delete(Request $request)
    {
        try {

            DB::beginTransaction();

            $api = Api::findOrFail($request->id);
            $api->delete();
            
            DB::commit();

            return [
                'state'     =>  'alert-success',
                'message'   =>  'El Api para '.strtoupper($request->descripcion).' se eliminó correctamente.'
            ];

        } catch (\Throwable $th) {
            DB::rollBack();

            return [
                'state'     =>  'alert-danger',
                'message'   =>  'Ocurrio un error al intentar eliminar el Api, intente nuevamente o contacte al Administrador del Sistema.'
            ];
        }
    }

    public function dni($numero)
    {
        if (!$cliente = Cliente::find($numero)) {

            $ruc = Api::where('descripcion', 'DNI')->where('activo', 'SI')->select('url', 'token', 'descripcion')->first();

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $ruc->url.'/'.$numero,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer ".$ruc->token,
                    "Content-Type: application/json"
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            
            return $response;
        } else {
            return [
                'cliente' => $cliente,
            ];
        }
    }

    public function ruc($numero)
    {
        if (!$cliente = Cliente::find($numero)) {
            $ruc = Api::where('descripcion', 'RUC')->where('activo', 'SI')->select('url', 'token', 'descripcion')->first();

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $ruc->url.'/'.$numero,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer ".$ruc->token,
                    "Content-Type: application/json"
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            
            return $response;
        } else {
            return [
                'cliente' => $cliente,
            ];
        }
    }

    public function proveedor($numero)
    {
        if (!$cliente = Proveedor::where('numero_documento', $numero)->first()) {
            $ruc = Api::where('descripcion', 'RUC')->where('activo', 'SI')->select('url', 'token', 'descripcion')->first();

            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => $ruc->url.'/'.$numero,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer ".$ruc->token,
                    "Content-Type: application/json"
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            
            return $response;
        } else {
            return [
                'cliente' => $cliente,
            ];
        }
    }
}
