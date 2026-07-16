<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\BarbeariaController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('auth.login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas para Admins (Super Admin)
    Route::resource('admins', AdminController::class);

    // Rotas para Barbearias
    Route::resource('barbearias', BarbeariaController::class);

    // Rotas para Serviços
    Route::resource('services', ServiceController::class);

    // Rotas para Usuários/Barbeiros
    Route::resource('users', UserController::class);
    
    // Rota específica para registrar atendimento de um usuário
    Route::post('/users/{user}/atendimentos', [UserController::class, 'storeAtendimento'])
        ->name('users.atendimentos.store');
});

require __DIR__.'/auth.php';