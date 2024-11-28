<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\DashboardController;
use App\Http\Middleware\SecureRoutes;
use Illuminate\Support\Facades\Route;

Route::name("branch.")->prefix("branch")->group(function () {
    Route::get("/", [BranchController::class, "index"]);
})->middleware(SecureRoutes::class);