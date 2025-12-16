<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Prestamo;
use Illuminate\Http\Request;

class PrestamoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Métodos para el Usuario Normal
    |--------------------------------------------------------------------------
    */

    /**
     * Muestra el catálogo de artículos disponibles a todos los usuarios.
     * Esta es la vista principal de la sección "Préstamo".
     */
    public function index()
    {
        $articulosDisponibles = Articulo::where('estado', 'disponible')->paginate(12);
        return view('prestamo.index', compact('articulosDisponibles'));
    }
    
    /**
     * Procesa la solicitud de un préstamo.
     */
    public function solicitar(Articulo $articulo)
    {
        // Lógica para solicitar...
        return redirect()->route('prestamos.mis-prestamos')->with('success', 'Tu solicitud ha sido enviada.');
    }

    /**
     * Muestra al usuario su historial de préstamos.
     */
    public function misPrestamos()
    {
        $misPrestamos = Prestamo::where('usuario_solicitante_id', auth()->id())->with('articulo')->latest()->paginate(10);
        return view('prestamo.mis-prestamos', compact('misPrestamos'));
    }

    /*
    |--------------------------------------------------------------------------
    | Métodos para el Administrador
    |--------------------------------------------------------------------------
    */

    /**
     * Muestra la tabla de gestión de TODOS los préstamos al administrador.
     */
    public function gestion()
    {
        $prestamos = Prestamo::with('articulo', 'usuario_solicitante')->latest()->paginate(15);
        return view('admin.prestamos.gestion', compact('prestamos'));
    }
    
    // ... Aquí van los otros métodos de admin: aprobar(), devolver(), destroy() ...
}