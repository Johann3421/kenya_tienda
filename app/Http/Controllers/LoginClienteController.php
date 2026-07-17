<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginClienteController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:cliente')->except('logout');
    }

    public function showLoginForm(Request $request)
    {
        // Guardamos el intended manual si viene por GET redirect
        if ($request->has('redirect')) {
            session(['url.intended' => $request->query('redirect')]);
        }
        return view('auth.login-cliente');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required'
        ]);

        $loginField = filter_var($credentials['username'], FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $authData = [
            $loginField => $credentials['username'],
            'password' => $credentials['password'],
            'activo' => 'SI'
        ];

        if (Auth::guard('cliente')->attempt($authData)) {
            $request->session()->regenerate();
            return redirect()->intended('/catalogo');
        }

        return back()->withErrors([
            'username' => 'Las credenciales no coinciden con nuestros registros o la cuenta está inactiva.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('cliente')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        // Al cerrar sesión en el portal, lo mandamos al index público
        return redirect('/');
    }
}
