<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Database\QueryException;

class UserController extends Controller
{
    public function formularioLogin()
    {
        if (Auth::check()) {
            return redirect()->route('backoffice.dashboard');
        }
        return view('usuario.login');
    }

    public function formularioNuevo()
    {
        if (Auth::check()) {
            return redirect()->route('backoffice.dashboard');
        }
        return view('usuario.create');
    }

    public function login(Request $request)
    {
        $mensajes = [
            'email.required' => 'El email es obligatorio',
            'email.email' => 'El email no tiene un formato válido',
            'password.required' => 'La contraseña es obligatoria',
        ];

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], $mensajes);

        $credenciales = $request->only('email', 'password');

        if (Auth::attempt($credenciales)) {
            $user = Auth::user();
            if (!$user->activo) {
                Auth::logout();
                return response()->json(['message' => 'El usuario se encuentra desactivado.'], 403);
            }
            $request->session()->regenerate();
            return redirect()->route('backoffice.dashboard');
        }
        return redirect()->back()->withErrors(['email' => 'El usuario o contraseña son incorrectos.'], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('usuario.login');
    }

    public function registrar(Request $request)
    {
        $mensajes = [
            'nombre.required' => 'El nombre es obligatorio.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'El email no tiene un formato válido.',
            'password.required' => 'La contraseña es obligatoria.',
            'rePassword.required' => 'Repetir la contraseña es obligatorio.',
            'dayCode.required' => 'El código del día es obligatorio.',
        ];

        $request->validate([
            'nombre' => 'required|string|max:50',
            'email' => 'required|email',
            'password' => 'required',
            'rePassword' => 'required',
            'dayCode' => 'required',
        ], $mensajes);

        $datos = $request->only('nombre', 'email', 'password', 'rePassword', 'dayCode');

        if ($datos['password'] != $datos['rePassword']) {
            return back()->withErrors(['message' => 'Las contraseñas no coinciden.'], 400);
        }

        if ($datos['dayCode'] != date("d")) {
            return back()->withErrors(['message' => 'Código del día no válido.'], 400);
        }

        try {
            User::create([
                'nombre' => $datos['nombre'],
                'email' => $datos['email'],
                'password' => Hash::make($datos['password']),
            ]);
            return redirect()->route('usuario.login')->with('success', 'Usuario creado exitosamente :)');
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return back()->withErrors(['message' => 'Error: el usuario ya existe.'], 409);
            }
            return back()->withErrors(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}