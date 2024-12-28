<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::name("auth.")->prefix("auth")->group(function () {
    Route::get("/", [AuthController::class,"index"])->name("index");
    Route::get("/login-otp", [AuthController::class,"displayOneTimePasswordView"]);
    Route::post("/login", [AuthController::class, "login"])->name("login");
    Route::get("/logout", [AuthController::class, "logout"])->name("logout")->middleware(\App\Http\Middleware\SecureRoutes::class);
    Route::post("/generate-otp", [AuthController::class, "generateOneTimePassword"]);
    Route::post("/consume-otp", [AuthController::class, "consumeOneTimePassword"]);

    Route::get('/get-employees', [AuthController::class, "fetchEmployees"])->middleware(\App\Http\Middleware\SecureRoutes::class);
});