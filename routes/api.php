<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// Ruta pública de registro de clientes
use App\Http\Controllers\Auth\RegisterController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [RegisterController::class, 'register']);
use App\Http\Controllers\Auth\LoginController;

Route::post('/login', [LoginController::class, 'login']);
