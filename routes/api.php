<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProductosController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('login', [AuthController::class, 'signin']);    //GOOD
Route::post('register', [AuthController::class, 'signup']); //GOOD


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function () {
    Route::resource('productos', ProductosController::class);
    Route::post('productos/search', [ProductosController::class, 'search']);
    Route::post('signoff',  [AuthController::class, 'signoff']);
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::apiResource('producto', ProductosController::class);
});
