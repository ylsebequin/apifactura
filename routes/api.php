<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\EmpresaContoller;
use App\Http\Controllers\ProductosController;
use App\Http\Controllers\VentaController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('empresas',EmpresaContoller::class)->middleware('auth.routes');
Route::apiResource('clientes',ClienteController::class)->middleware('auth.routes');
Route::apiResource('productos',ProductosController::class)->middleware('auth.routes');
Route::apiResource('ventas',VentaController::class)->middleware('auth.routes');