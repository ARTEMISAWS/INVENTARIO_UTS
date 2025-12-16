<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\ArticulosdañadosController;
use App\Http\Controllers\UsuariosController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/prestamo', [PrestamoController::class, 'index'])->name('prestamo.index');
    Route::get('/mis-prestamos', [PrestamoController::class, 'misPrestamos'])->name('prestamos.mis-prestamos');
    Route::post('/prestamos/solicitar/{articulo}', [PrestamoController::class, 'solicitar'])->name('prestamos.solicitar');
});



Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::resource('inventario', InventarioController::class);
    Route::resource('prestamo', PrestamoController::class);
    Route::resource('articulosdañados', ArticulosdañadosController::class);
    Route::resource('usuarios', UsuariosController::class);
   

    Route::get('/prestamo', [PrestamoController::class, 'gestion'])->name('admin.prestamo.gestion');
    Route::patch('/prestamo/{prestamo}/aprobar', [PrestamoController::class, 'aprobar'])->name('prestamo.aprobar');
    Route::patch('/prestamo/{prestamo}/devolver', [PrestamoController::class, 'devolver'])->name('prestamo.devolver');
    Route::delete('/prestamo/{prestamo}', [PrestamoController::class, 'destroy'])->name('prestamos.destroy');
});


require __DIR__.'/auth.php';
