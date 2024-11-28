<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\SecureRoutes;
use Illuminate\Support\Facades\Route;

Route::name("dashboard.")->prefix("dashboard")->group(function () {
    Route::get("/", [DashboardController::class, "index"])->name("main")->middleware(SecureRoutes::class);
})->middleware(SecureRoutes::class);