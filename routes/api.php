<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NomesApiController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', [NomesApiController::class, 'index']); //TOP 10
Route::get('/nome/{nome?}', [NomesApiController::class, 'nome']); //NOME
Route::get('/decada-mais-frequente/{nome?}', [NomesApiController::class, 'decada_mais_frequente']);

Route::fallback(function () { //CASO A ROTA BUSCADA NÃO EXISTA
    return response()->json(['erro' => 'Recurso inexistente'], 404);
});
