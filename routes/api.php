<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(["prefix"=>"auth"], function(){
    Route::post("register", [AuthController::class, "register"]);
    Route::post("login", [AuthController::class, "login"]);
});

Route::group(["middleware"=>[\App\Http\Middleware\CheckAuth::class]], function(){
    Route::post("/auth/logout", [AuthController::class, "logout"]);
});
