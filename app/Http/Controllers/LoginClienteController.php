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

        $user = \App\User::where($loginField, $credentials['username'])->first();

        if ($user) {
            $passCheck = \Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password);
            
            // Retornamos JSON de depuración temporal para el VPS
            return response()->json([
                'info' => 'Usuario encontrado',
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_username' => $user->username,
                'user_activo' => $user->activo,
                'password_ingresado' => $credentials['password'],
                'password_hash_db' => $user->password,
                'check_resultado' => $passCheck,
                'roles' => method_exists($user, 'getRoleNames') ? $user->getRoleNames() : 'No roles method'
            ]);
        }

        return response()->json([
            'info' => 'Usuario NO encontrado',
            'login_field_usado' => $loginField,
            'valor_buscado' => $credentials['username'],
            'total_usuarios_db' => \App\User::count()
        ]);

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
