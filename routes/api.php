<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CalidadAireController;


//http://localhost:8000/api/device/data
Route::post('/device/data', [CalidadAireController::class, 'storeDeviceData']);

Route::get('/dashboard', [CalidadAireController::class, 'dashboard']);

Route::get('/device/co', [CalidadAireController::class, 'coBetween']);
Route::get('/device/nox', [CalidadAireController::class, 'noxBetween']);
Route::get('/device/sox', [CalidadAireController::class, 'soxBetween']);
Route::get('/device/pm10', [CalidadAireController::class, 'pm10Between']);
Route::get('/device/pm25', [CalidadAireController::class, 'pm25Between']);


//NUEVO: Endpoints para obtener los últimos registros o todos los registros
Route::get('/device/latest', [CalidadAireController::class, 'latest']);
Route::get('/device/all', [CalidadAireController::class, 'allRecords']);
Route::get('/device/since', [CalidadAireController::class,'since']);


// Obtener todos los registros de un día específico
Route::get('/device/by-date', [CalidadAireController::class, 'ByDate']);
// NUEVO: Obtener los últimos registros de un día específico
Route::get('/device/latest-by-date', [CalidadAireController::class, 'LatestByDate']);








