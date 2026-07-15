<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\BarbeariaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ServiceController;
Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    
    Route::resource('admins', AdminController::class);
    Route::resource('barbearias', BarbeariaController::class);
    Route::resource('users', UserController::class);
    Route::resource('services', ServiceController::class);
    Route::post('users/{user}/atendimentos', [UserController::class, 'storeAtendimento'])->name('users.atendimentos.store');
});

require __DIR__.'/settings.php';
