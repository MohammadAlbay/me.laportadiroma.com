<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HolasaciController;
use App\Http\Controllers\LaPortaDiRomaDashboardController;
use App\Http\Middleware\SecureRoutes;
use Illuminate\Support\Facades\Route;

Route::name("laportadiroma.")->prefix("laportadiroma")->group(function () {
    Route::get("/", [LaPortaDiRomaDashboardController::class, "index"])->name("main")->middleware(SecureRoutes::class);
    Route::post('/create-new-asset', [AssetController::class, "createNewAssetType"]);
    Route::post('/add-new-asset', [AssetController::class, "addNewAsset"]);
    Route::get("/get-asset-types", [AssetController::class, "getAssetTypes"]);
    //laportadiroma/get-asset-types

})->middleware(SecureRoutes::class);


//holasaci
Route::name("holasaci.")->prefix("holasaci")->group(function () {
    Route::get("/", [HolasaciController::class, "index"])->name("main")->middleware(SecureRoutes::class);
})->middleware(SecureRoutes::class);