<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
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
    
    // Método para registrar un nuevo usuario
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Error en la validación', 'errors' => $validator->errors()], 400);
        }

        $user = User::create([
            'nombre' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'activo' => true, // Asumiendo que el usuario se activa al registrarse
        ]);

        return response()->json(['message' => 'Usuario registrado con éxito'], 201);
    }

    // Método para iniciar sesión
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Error en la validación', 'errors' => $validator->errors()], 400);
        }

        $credentials = $request->only('email', 'password');

        if (!$token = JWTAuth::attempt($credentials)) {
            Log::error('Login failed for email: ' . $request->email);
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        Log::info('Login successful for email: ' . $request->email);
        return $this->respondWithToken($token);
    }

    // Método para obtener el usuario autenticado
    public function me()
    {
        return response()->json(JWTAuth::parseToken()->authenticate());
    }

    // Método para cerrar sesión
    public function logout()
    {
        JWTAuth::invalidate(JWTAuth::getToken());
        return response()->json(['message' => 'Sesión cerrada con éxito']);
    }

    // Método para refrescar el token
    public function refresh()
    {
        $token = JWTAuth::refresh(JWTAuth::getToken());
        return $this->respondWithToken($token);
    }

    // Método para responder con el token
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => JWTAuth::factory()->getTTL() * 60
        ]);
    }
}
