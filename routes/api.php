<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\TokenController;

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

Route::post('register', [CustomAuthController::class, 'register']);
Route::post('login', [CustomAuthController::class, 'login']);
Route::group(['middleware' => 'auth:token'], function(){
    Route::group(['middleware' => 'log'], function(){
        Route::post('token', [TokenController::class, 'create']);
        Route::delete('token', [TokenController::class, 'delete']);
    });
});