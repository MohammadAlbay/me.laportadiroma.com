<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\MigrationController;
use Illuminate\Support\Facades\Route;

Route::name("view-loader.")->prefix("view-loader")->group(function () {
    Route::get("/main",[MainController::class, "mainView"]);
    Route::get("/create-asset-type", [MigrationController::class, "index"]);
})->middleware(\App\Http\Middleware\SecureRoutes::class);