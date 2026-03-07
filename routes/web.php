<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\PrestamoController;
use App\Http\Controllers\ArticulosdañadosController;
use App\Http\Controllers\UsuariosController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\SubCategoriaController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $totalArticulos = \App\Models\Articulo::count();
    $articulosPrestados = \App\Models\Articulo::where('estado', 'prestado')->count();
    $articulosDisponibles = \App\Models\Articulo::where('estado', 'disponible')->count();
    $articulosMantenimiento = \App\Models\Articulo::where('estado', 'en_mantenimiento')->count();

    return view('dashboard', compact('totalArticulos', 'articulosPrestados', 'articulosDisponibles', 'articulosMantenimiento'));
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/prestamos/{subcategoria?}', [PrestamoController::class, 'index'])->name('prestamos.index');
    Route::get('/mis-prestamos', [PrestamoController::class, 'misPrestamos'])->name('prestamos.mis-prestamos');

    // Rutas del Carrito de Compras (Solicitud Múltiple)
    Route::get('/carrito', [\App\Http\Controllers\CarritoController::class, 'index'])->name('carrito.index');
    Route::post('/carrito/agregar/{articulo}', [\App\Http\Controllers\CarritoController::class, 'agregar'])->name('carrito.agregar');
    Route::delete('/carrito/eliminar/{id}', [\App\Http\Controllers\CarritoController::class, 'eliminar'])->name('carrito.eliminar');
    Route::post('/carrito/vaciar', [\App\Http\Controllers\CarritoController::class, 'vaciar'])->name('carrito.vaciar');
    Route::post('/carrito/procesar', [\App\Http\Controllers\CarritoController::class, 'procesar'])->name('carrito.procesar');

    // Route::post('/prestamos/solicitar/{articulo}', [PrestamoController::class, 'solicitar'])->name('prestamos.solicitar'); // Deprecated
});



Route::middleware(['auth', 'role:admin,superadmin'])->group(function () {
    Route::resource('inventario', InventarioController::class);
    Route::resource('prestamo', PrestamoController::class);
    Route::resource('articulosdañados', ArticulosdañadosController::class);
    Route::resource('usuarios', UsuariosController::class);
    Route::resource('categorias', CategoriaController::class);
    Route::resource('subcategorias', SubCategoriaController::class);

    // --- RUTAS DE APROBACIÓN ---
    Route::post('/prestamo/vistaAprobacion', [PrestamoController::class, 'vistaAprobacion'])->name('prestamos.vistaAprobacion');
    Route::post('/prestamo/guardarAprobacion', [PrestamoController::class, 'guardarAprobacion'])->name('prestamos.guardarAprobacion');

    // --- RUTAS DE DEVOLUCIÓN  ---
    Route::post('/prestamo/vistaDevolucion', [PrestamoController::class, 'vistaDevolucion'])->name('prestamos.vistaDevolucion');
    Route::post('/prestamo/guardarDevolucion', [PrestamoController::class, 'guardarDevolucion'])->name('prestamos.guardarDevolucion');

    Route::patch('/prestamo/{prestamo}/aprobar', [PrestamoController::class, 'aprobar'])->name('prestamos.aprobar');
    Route::patch('/prestamo/{prestamo}/devolver', [PrestamoController::class, 'devolver'])->name('prestamos.devolver');
    Route::delete('/prestamo/{prestamo}', [PrestamoController::class, 'destroy'])->name('prestamos.destroy');

    Route::get('/prestamos', [PrestamoController::class, 'index'])->name('prestamos.index');
    Route::get('/prestamos/verArticulos/{subcategoria}', [PrestamoController::class, 'verArticulos'])->name('prestamos.verArticulos');
});


require __DIR__ . '/auth.php';
