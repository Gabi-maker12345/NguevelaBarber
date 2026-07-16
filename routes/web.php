<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BarbeariaController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
})->name('home');

Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');


// Área do Admin
Route::middleware(['auth:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admins.index');
    Route::get('/admins', [AdminController::class, 'index'])->name('admins.dashboard');
    Route::resource('admins', AdminController::class)->except(['index']);

    Route::resource('barbearias', BarbeariaController::class)->except(['update']);
});

Route::match(['put', 'patch'], '/barbearias/{barbearia}', [BarbeariaController::class, 'update'])
    ->middleware(['auth:admin,barbearia'])
    ->name('barbearias.update');

Route::post('/users/{user}/atendimentos', [UserController::class, 'storeAtendimento'])
    ->middleware(['auth:web,barbearia'])
    ->name('users.atendimentos.store');

// Área da Barbearia
Route::middleware(['auth:barbearia'])->group(function () {
    Route::get('/barbearia/dashboard', [BarbeariaController::class, 'index'])->name('barbearias.dashboard');

    Route::post('/barbearias/{barbearia}/users', [UserController::class, 'storeForBarbearia'])
        ->name('barbearias.users.store');
    Route::put('/barbearias/{barbearia}/users/{user}', [UserController::class, 'updateForBarbearia'])
        ->name('barbearias.users.update');
    Route::delete('/barbearias/{barbearia}/users/{user}', [UserController::class, 'destroyForBarbearia'])
        ->name('barbearias.users.destroy');

    Route::resource('services', ServiceController::class);
});

// Área do Usuário (Barbeiro)
Route::middleware(['auth:web'])->group(function () {
    Route::get('/user/dashboard', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::match(['put', 'patch'], '/users/{user}', [UserController::class, 'update'])->name('users.update');
});

require __DIR__.'/auth.php';
require __DIR__.'/settings.php';
