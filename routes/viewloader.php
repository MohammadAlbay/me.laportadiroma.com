<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\LaPortaDiRomaDashboardController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\MigrationController;
use Illuminate\Support\Facades\Route;

Route::name("view-loader.")->prefix("view-loader")->group(function () {
    Route::get("/main",[MainController::class, "mainView"]);
    Route::get("/create-asset-type", [MigrationController::class, "index"]);
    Route::get("/settings", [LaPortaDiRomaDashboardController::class, "displaySettings"]);
    Route::get("/asset-new-type", [AssetController::class, "dispayAssetNewTypeView"]);
})->middleware(\App\Http\Middleware\SecureRoutes::class);