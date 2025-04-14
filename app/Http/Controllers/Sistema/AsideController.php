<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\Controller;
use App\Models\Aside;
use App\Modelo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AsideController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:filtros')->except(['show']);
    }

    public function index()
    {
        $asides = Aside::with('modelo')->orderBy('nombre_aside')->get();
        return view('sistema.aside.index', compact('asides'));
    }

    public function create()
    {
        $modelos = Modelo::where('activo', 'Si')->get(['id', 'descripcion']);
        return view('sistema.aside.modals.nuevo', compact('modelos'));
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'modelo_id' => 'required|exists:modelos,id',
        'nombre_aside' => 'required|string|max:100|unique:asides,nombre_aside',
        'opciones' => 'required|json'
    ]);

    $opciones = json_decode($request->opciones, true);

    $aside = Aside::create([
        'modelo_id' => $request->modelo_id,
        'nombre_aside' => $request->nombre_aside,
        'opciones' => $opciones,
        'activo' => true
    ]);

    return redirect()->route('sistema.aside.index')
        ->with('success', 'Filtro creado correctamente');
}

    public function show(Aside $aside)
    {
        return view('sistema.aside.detalle', compact('aside'));
    }

    public function edit(Aside $aside)
    {
        $modelos = Modelo::where('activo', 'Si')->get(['id', 'descripcion']);
        return view('sistema.aside.modals.editar', compact('aside', 'modelos'));
    }

    public function update(Request $request, Aside $aside)
{
    $validated = $request->validate([
        'modelo_id' => 'required|exists:modelos,id',
        'nombre_aside' => [
            'required',
            'string',
            'max:100',
            Rule::unique('asides')->ignore($aside->id)
        ],
        'opciones' => 'required|json'
    ]);

    $aside->update([
        'modelo_id' => $request->modelo_id,
        'nombre_aside' => $request->nombre_aside,
        'opciones' => json_decode($request->opciones, true)
    ]);

    return redirect()->route('sistema.aside.index')
        ->with('success', 'Filtro actualizado correctamente');
}

    public function destroy(Aside $aside)
    {
        $aside->delete();
        return redirect()->route('sistema.aside.index')
            ->with('success', 'Filtro eliminado exitosamente');
    }

    public function agregarOpcion(Request $request, Aside $aside)
    {
        $request->validate([
            'opcion' => 'required|string|max:50|unique:asides,opciones->*'
        ], [
            'opcion.unique' => 'Esta opción ya existe en el filtro'
        ]);

        $opciones = $aside->opciones ?? [];

        if (!in_array($request->opcion, $opciones)) {
            $opciones[] = $request->opcion;
            $aside->update(['opciones' => $opciones]);
            return back()->with('success', 'Opción agregada correctamente');
        }

        return back()->with('error', 'La opción ya existe en este filtro');
    }

    public function eliminarOpcion(Request $request, Aside $aside)
    {
        $request->validate(['indice' => 'required|integer']);

        $opciones = $aside->opciones;

        if (isset($opciones[$request->indice])) {
            unset($opciones[$request->indice]);
            $aside->update(['opciones' => array_values($opciones)]);
            return back()->with('success', 'Opción eliminada correctamente');
        }

        return back()->with('error', 'Índice de opción no válido');
    }

    public function toggleStatus(Aside $aside)
    {
        $aside->update(['activo' => !$aside->activo]);
        return back()->with('success', 'Estado del filtro actualizado');
    }
}
