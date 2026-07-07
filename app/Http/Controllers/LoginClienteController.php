<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginClienteController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
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
            'password' => $credentials['password']
        ];

        if (Auth::attempt($authData)) {
            $request->session()->regenerate();

            if (Auth::user()->hasRole('cliente_web')) {
                return redirect()->intended('/catalogo');
            }

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'username' => 'Acceso denegado. Este portal es exclusivo para clientes verificados.',
            ]);
        }

        return back()->withErrors([
            'username' => 'Las credenciales no coinciden con nuestros registros.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        // Al cerrar sesión en el portal, lo mandamos al index público
        return redirect('/');
    }
}
