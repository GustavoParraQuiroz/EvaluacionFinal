<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProyectoController;
use Illuminate\Support\Facades\Log;

// Rutas públicas
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/refresh', [AuthController::class, 'refresh']);
Route::post('/logout', [AuthController::class, 'logout']);

// Rutas protegidas con middleware JWT
Route::get('/user', [AuthController::class, 'me'])->middleware('jwt.auth');
Route::apiResource('proyectos', ProyectoController::class)->middleware('jwt.auth');

Route::middleware(['jwt.auth'])->group(function () {
    Log::info('middleware');
});
