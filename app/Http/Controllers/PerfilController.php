<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PerfilController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        return view('perfil.index', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nombres' => 'required|string|max:255',
            'ape_paterno' => 'nullable|string|max:255',
            'ape_materno' => 'nullable|string|max:255',
            'dni' => 'nullable|string|max:50',
            'telefono' => 'nullable|string|max:50',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'username' => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'password' => 'nullable|confirmed|min:6',
        ]);

        $data = $request->only(['dni', 'nombres', 'ape_paterno', 'ape_materno', 'telefono', 'email', 'username']);

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success', 'Perfil actualizado correctamente.');
    }
}
