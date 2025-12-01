<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
// Ruta pública de registro de clientes
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\CalidadAireController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [RegisterController::class, 'register']);
use App\Http\Controllers\Auth\LoginController;

Route::post('/login', [LoginController::class, 'login']);

// Rutas públicas para datos de calidad del aire
Route::get('/dashboard', [CalidadAireController::class, 'dashboard']);
Route::get('/co2', [CalidadAireController::class, 'co2']);
Route::get('/nox', [CalidadAireController::class, 'nox']);
Route::get('/sox', [CalidadAireController::class, 'sox']);
Route::get('/pm10', [CalidadAireController::class, 'pm10']);
Route::get('/pm25', [CalidadAireController::class, 'pm25']);
Route::get('/temp', [CalidadAireController::class, 'temp']);
Route::get('/hum', [CalidadAireController::class, 'hum']);

// Endpoints del dispositivo
Route::post('/device/data', [CalidadAireController::class, 'storeDeviceData']);
Route::get('/device/latest', [CalidadAireController::class, 'latest']);
Route::get('/device/all', [CalidadAireController::class, 'allRecords']);
Route::get('/device/since', [CalidadAireController::class, 'since']);
Route::get('/device/by-date', [CalidadAireController::class, 'ByDate']);
Route::get('/device/latest-by-date', [CalidadAireController::class, 'LatestByDate']);
Route::get('/device/today-average', [CalidadAireController::class, 'todayAverage']);
Route::get('/device/export-csv', [CalidadAireController::class, 'exportCsv']);

// Rutas auxiliares
Route::get('/co2/between', [CalidadAireController::class, 'coBetween']);
