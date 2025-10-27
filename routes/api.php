<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\LocationController;
use App\Http\Middleware\CheckAdmin;
use Illuminate\Support\Facades\Route;

// авторизация
Route::group(["prefix"=>"auth"], function(){
    Route::post("register", [AuthController::class, "register"]);
    Route::post("login", [AuthController::class, "login"]);
});

// только авторизованным
Route::group(["middleware"=>[\App\Http\Middleware\CheckAuth::class]], function(){
    Route::post("/auth/logout", [AuthController::class, "logout"]);

    Route::get("/feedbacks/my");
    Route::post("/feedbacks", [FeedbackController::class, "store"]);
    Route::patch("/feedbacks/{feedback}", [FeedbackController::class, "update"]);

    Route::group(["middleware"=>[CheckAdmin::class]], function(){
        Route::post("/locations", [LocationController::class, "create"]);
        Route::patch("/locations/{location}", [LocationController::class, "update"]);
        Route::delete("/locations/{location}", [LocationController::class, "destroy"]);
    });
});


// публичные
Route::get("/locations", [LocationController::class, "index"]);
Route::get("/feedbacks", [FeedbackController::class, "index"]);
Route::get("/feedbacks/{feedback}", [FeedbackController::class, "show"]);
