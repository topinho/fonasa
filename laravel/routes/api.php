<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\InicioController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\AtencionController;
use App\Http\Controllers\ConsultaController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('inicio', [InicioController::class, 'index']);
Route::get('pacientes', [PacienteController::class, 'index']);
Route::post('atenciones', [AtencionController::class, 'store']);
Route::post('consultas/liberar', [ConsultaController::class, 'liberar']);
Route::get('consultas/mas-atenciones', [ConsultaController::class, 'mas_atenciones']);
Route::post('atenciones/optimizar', [AtencionController::class, 'optimizar']);