<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProyectoController;
use App\Http\Middleware\JwtMiddleware;

Route::get('/', function () {
    return view('landing.index');
})->name('raiz');

Route::get('/login', [UserController::class, 'formularioLogin'])->name('usuario.login');
Route::post('/login', [UserController::class, 'login'])->name('usuario.validar');

Route::post('/logout', [UserController::class, 'logout'])->name('usuario.logout');

Route::get('/users/register', [UserController::class, 'formularioNuevo'])->name('usuario.registrar');
Route::post('/users/register', [UserController::class, 'registrar'])->name('usuario.registrar');

Route::get('/backoffice', function () {
    $user = Auth::user();
    if ($user == NULL) {
        return redirect()->route('usuario.login')->withErrors(['message' => 'No existe una sesión activa']);
    }
    return view('backoffice.dashboard', ['user' => $user]);
})->name('backoffice.dashboard');

//RUTAS PROYECTOS CON MIDDLEWARE
Route::middleware([JwtMiddleware::class])->group(function () {
    Route::get('/proyectos', [ProyectoController::class, 'index']);
    Route::post('/proyectos', [ProyectoController::class, 'store']);
    Route::get('/proyectos/{id}', [ProyectoController::class, 'show']);
    Route::put('/proyectos/{id}', [ProyectoController::class, 'update']);
    Route::delete('/proyectos/{id}', [ProyectoController::class, 'destroy']);
});