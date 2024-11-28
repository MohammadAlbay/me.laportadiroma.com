<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MainController;
use App\Http\Middleware\SimplePasswordAuth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;



Route::get('/', function() {
    return view('index');
})->name('home');

Route::name('view-loader.')->prefix('view-loader')->group(function () {
    Route::get('/main', [MainController::class, 'index'])->name('main');
});

Route::get('/hash/{key}', function($key) {
    return "Key:".Hash::make($key);
});


Route::middleware(SimplePasswordAuth::class)->group(function () {

    Route::get('/list-emails', [\App\Http\Controllers\CPanelController::class, 'getEmailList']);
    Route::get('/list-plain-emails', [\App\Http\Controllers\CPanelController::class, 'getEmailListPlainText']);
    Route::get('/generate-emails-from-origin', [\App\Http\Controllers\CPanelController::class, 'getEmailPasswordList']);
    Route::get('/generate-save', [\App\Http\Controllers\CPanelController::class, 'generateThenSaveEmails']);
    // DEBUG Only routes
    Route::get('/test-email', function() {
        Mail::to('mohamed.albay@laportadiroma.com')->send(new \App\Mail\SendUserPassword("Mohammad Albay", "1234"));
        return "Done";
    });
});


include_once __DIR__ ."/auth.php";
include_once __DIR__ ."/dashboard.php";
include_once __DIR__ ."/viewloader.php";
include_once __DIR__ ."/branches.php";
