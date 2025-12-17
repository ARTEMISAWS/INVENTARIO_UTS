<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Prestamo;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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
   /*  public function index()
    {
        if (Auth::user()->role === 'admin' || Auth::user()->role === 'superadmin') {
            $articulosDisponibles = Articulo::where('estado', 'disponible')->paginate(12);
            return view('prestamo.indexAdmin', compact('articulosDisponibles'));
        }

        if (Auth::user()->role === 'usuario') {
            $articulosDisponibles = Articulo::where('estado', 'disponible')->paginate(12);
            return view('prestamo.index', compact('articulosDisponibles'));
        }
    } */

    public function index(Request $request, $subcategoriaId = null)
    {
        $search = $request->get('search');

        $prestamos = Prestamo::with(['articulo', 'usuario_solicitante', 'usuario_despacha'])
            ->when($search, function ($query) use ($search) {
                $query->whereHas('articulo', function ($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%");
                })->orWhereHas('usuario_solicitante', function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if (Auth::user()->role === 'admin' || Auth::user()->role === 'superadmin') {
            return view('prestamo.indexAdmin', compact('prestamos', 'search'));
        }

        if (Auth::user()->role === 'usuario') {
            // 1. Cargamos categorías con sus subcategorías y el conteo de artículos disponibles
            $categorias = Categoria::with(['subcategorias' => function($query) {
                $query->withCount(['articulos as cantidad_disponible' => function($q) {
                    $q->where('estado', 'Bueno'); // Filtramos solo los que están en buen estado
                }]);
            }])->get();

            // 2. Cargamos los artículos aplicando filtros (Buscador o Categoría lateral)
            $articulosDisponibles = Articulo::where('estado', 'Bueno')
                ->when($search, function ($query) use ($search) {
                    $query->where(function($q) use ($search) {
                        $q->where('nombre', 'like', "%{$search}%")
                        ->orWhere('marca', 'like', "%{$search}%")
                        ->orWhere('modelo', 'like', "%{$search}%");
                    });
                })
                ->when($subcategoriaId, function ($query) use ($subcategoriaId) {
                    $query->where('subcategoria_id', $subcategoriaId);
                })
                ->latest()
                ->paginate(12)
                ->withQueryString(); // Esto mantiene el filtro al cambiar de página

            return view('prestamo.index', compact('categorias', 'articulosDisponibles', 'search'));
        }
    }
    
    /**
     * Procesa la solicitud de un préstamo.
     */
    public function solicitar(Articulo $articulo)
    {
        if(Prestamo::where('articulo_id', $articulo->id)->whereIn('estado', ['Pendiente', 'Activo'])->exists()) 
        {
            return redirect()->route('prestamos.mis-prestamos')->with('success', 'No puedes solicitar el mismo articulo.');
            /* return redirect()->back()->with('error', 'No puedes solicitar este artículo en este momento.'); */
        }

        Prestamo::create([
            'articulo_id' => $articulo->id,
            'usuario_solicitante_id' => auth()->id(),
            'fecha_prestamo'         => now()->toDateTimeString(),
            'fecha_devolucion_estimada'  => now()->addHour()->addMinutes(30)->toDateTimeString(),
            'estado'  => 'Pendiente',
        ]);

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

}