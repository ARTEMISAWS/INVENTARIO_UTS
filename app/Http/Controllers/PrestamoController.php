<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Articulo;
use App\Models\Prestamo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PrestamoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Métodos para el Panel de Administración
    |--------------------------------------------------------------------------
    */

    /**
     * Muestra la lista de todos los préstamos para el admin.
     */
    public function index()
    {
        $prestamos = Prestamo::with('articulo', 'usuario_solicitante')->latest()->paginate(15);
        return view('prestamo.index', compact('prestamos'));
    }

    /**
     * Muestra el formulario para que el admin cree un préstamo manualmente.
     */
    public function create()
    {
        $articulos = Articulo::where('estado', 'disponible')->get();
        $usuarios = User::all();
        return view('prestamo.create', compact('articulos', 'usuarios'));
    }
    
    /**
     * Guarda un préstamo creado manualmente por el admin.
     */
    public function store(Request $request)
    {
        $request->validate([
            'articulo_id' => 'required|exists:articulos,id',
            'usuario_solicitante_id' => 'required|exists:users,id',
            'fecha_devolucion_estimada' => 'required|date|after_or_equal:today',
        ]);

        $prestamo = Prestamo::create([
            'articulo_id' => $request->articulo_id,
            'usuario_solicitante_id' => $request->usuario_solicitante_id,
            'fecha_devolucion_estimada' => $request->fecha_devolucion_estimada,
            'observaciones_prestamo' => $request->observaciones_prestamo,
            'usuario_despacha_id' => auth()->id(),
            'fecha_prestamo' => now(),
            'estado' => 'aprobado', // Los préstamos manuales se aprueban al instante
        ]);

        $prestamo->articulo->update(['estado' => 'prestado']);

        return redirect()->route('prestamos.index')->with('success', 'Préstamo registrado exitosamente.');
    }

    /**
     * Aprueba una solicitud de préstamo pendiente.
     */
    public function aprobar(Prestamo $prestamo)
    {
        $prestamo->update([
            'estado' => 'aprobado',
            'usuario_despacha_id' => auth()->id(),
            'fecha_devolucion_estimada' => now()->addDays(7), // Por defecto 7 días, puedes cambiarlo
        ]);

        $prestamo->articulo->update(['estado' => 'prestado']);

        return back()->with('success', 'Préstamo aprobado exitosamente.');
    }

    /**
     * Registra la devolución de un artículo.
     */
    public function devolver(Prestamo $prestamo)
    {
        $prestamo->update([
            'estado' => 'devuelto',
            'usuario_recibe_id' => auth()->id(),
            'fecha_devolucion_real' => now(),
        ]);

        $prestamo->articulo->update(['estado' => 'disponible']);

        return back()->with('success', 'Devolución registrada exitosamente.');
    }

    /**
     * Rechaza una solicitud o cancela un préstamo.
     */
    public function destroy(Prestamo $prestamo)
    {
        if ($prestamo->estado === 'aprobado') {
            return back()->with('error', 'No se puede eliminar un préstamo activo. Primero debe ser devuelto.');
        }

        // Si el préstamo es rechazado, el artículo vuelve a estar disponible
        if ($prestamo->estado === 'solicitado') {
             $prestamo->articulo->update(['estado' => 'disponible']);
        }

        $prestamo->delete();
        return back()->with('success', 'Solicitud de préstamo rechazada/cancelada.');
    }


    /*
    |--------------------------------------------------------------------------
    | Métodos para el Usuario Normal
    |--------------------------------------------------------------------------
    */

    /**
     * Muestra el catálogo de artículos disponibles.
     */
    public function catalogo()
    {
        $articulosDisponibles = Articulo::where('estado', 'disponible')->paginate(12);
        return view('prestamo.catalogo', compact('articulosDisponibles'));
    }

    /**
     * Procesa la solicitud de un préstamo.
     */
    public function solicitar(Articulo $articulo)
    {
        if ($articulo->estado !== 'disponible') {
            return back()->with('error', 'El artículo ya no está disponible.');
        }

        // Marcamos el artículo como 'solicitado' para evitar que otro lo pida mientras se aprueba
        $articulo->update(['estado' => 'solicitado']);

        Prestamo::create([
            'articulo_id' => $articulo->id,
            'usuario_solicitante_id' => auth()->id(),
            'estado' => 'solicitado',
            'fecha_prestamo' => now(),
        ]);

        return redirect()->route('prestamos.mis-prestamos')->with('success', 'Tu solicitud ha sido enviada.');
    }

    /**
     * Muestra al usuario su historial de préstamos.
     */
    public function misPrestamos()
    {
        $misPrestamos = Prestamo::where('usuario_solicitante_id', auth()->id())
                                ->with('articulo')
                                ->latest()
                                ->paginate(10);

        return view('prestamo.mis-prestamos', compact('misPrestamos'));
    }
}