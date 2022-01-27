<?php

use App\Http\Controllers\JWTController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(["middleware"=>"jwtAuth"],function(){
    Route::post("/register",[JWTController::class,'register'])->name("register");
    Route::post("/login",[JWTController::class,'login'])->name("login");
    Route::post("/logout",[JWTController::class,'logout']);
    Route::post("/profile",[JWTController::class,'profile']);
});
