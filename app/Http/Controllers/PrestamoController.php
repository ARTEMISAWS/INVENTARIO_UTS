<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Prestamo;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $prestamos = Prestamo::with('usuario_solicitante')->where('id_padre', null)->paginate(10);
        /*  $prestamos = Prestamo::with(['articulo', 'usuario_solicitante', 'usuario_despacha'])
            ->select('prestamos.*')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('articulo', function ($sub) use ($search) {
                        $sub->where('nombre', 'like', "%{$search}%");
                    })->orWhereHas('usuario_solicitante', function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%");
                    });
                });
            })
            ->groupBy('prestamos.id')
            ->paginate(10)
            ->withQueryString(); */


        if (Auth::user()->role === 'admin' || Auth::user()->role === 'superadmin') {
            return view('prestamo.indexAdmin', compact('prestamos', 'search'));
        }

        if (Auth::user()->role === 'usuario') {
            // 1. Cargamos categorías con sus subcategorías
            // Usamos withCount en las subcategorías para contar artículos filtrados por estado 'disponible'
            $categorias = Categoria::with(['subcategorias' => function ($query) {
                $query->withCount(['articulos as cantidad_disponible' => function ($q) {
                    $q->where('estado', 'disponible');
                }]);
            }])->get();

            // 2. Cargamos los artículos aplicando filtros
            $articulosDisponibles = Articulo::with('subcategoria.categoria')->where('estado', 'disponible')
                // El buscador ahora incluye también el campo 'codigo_uts' que aparece en tu nuevo modelo
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('nombre', 'like', "%{$search}%")
                        ->orWhere('marca', 'like', "%{$search}%")
                        ->orWhere('modelo', 'like', "%{$search}%")
                        ->orWhere('codigo_uts', 'like', "%{$search}%"); // Agregado según tu esquema
                    });
                })
                // Filtro por subcategoría lateral
                ->when($subcategoriaId, function ($query) use ($subcategoriaId) {
                    $query->where('subcategoria_id', $subcategoriaId);
                })
                ->latest()
                ->paginate(12)
                ->withQueryString();

                /* dd($articulosDisponibles->toArray()); */

            return view('prestamo.index', compact('categorias', 'articulosDisponibles', 'search'));
        }
    }

    /**
     * Procesa la solicitud de un préstamo.
     */
    public function solicitar(Articulo $articulo)
    {
        if (Prestamo::where('articulo_id', $articulo->id)->whereIn('estado', ['Pendiente', 'Activo'])->exists()) {
            return redirect()->route('prestamos.mis-prestamos')->with('success', 'No puedes solicitar el mismo articulo.');
            /* return redirect()->back()->with('error', 'No puedes solicitar este artículo en este momento.'); */
        }

        Prestamo::create([
            'articulo_id' => $articulo->id,
            'usuario_solicitante_id' => Auth::user()->id,
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

        $misPrestamos = Prestamo::where('usuario_solicitante_id', Auth::user()->id)
                                ->where('id_padre', '!=', null)
                               ->with(['articulo', 'padre'])
                                ->latest()
                                ->paginate(10);
        return view('prestamo.mis-prestamos', compact('misPrestamos'));
    }

    public function vistaAprobacion(Request $request)
    {
        $misProductos = Prestamo::where('usuario_solicitante_id', $request->usuario_solicitante_id)
            ->where(function ($query) use ($request) {
                $query->where('id_padre', $request->id_prestamo);
            })
            ->with('articulo.subcategoria.categoria')
            ->latest()->get();
        return view('prestamo.vistaAprobacion', compact('misProductos'));
    }

    public function guardarAprobacion(Request $request)
    {
        $request->validate([
            'fecha_devolucion_estimada' => 'required|date',
            'observaciones_prestamo' => 'nullable|string',
            'usuario_solicitante_id' => 'required',
            'id_prestamo' => 'required'
        ]);

        $prestamos = Prestamo::where('id', $request->id_prestamo)->first();
        $prestamos->update([
            'fecha_devolucion_estimada' => $request->fecha_devolucion_estimada,
            'observaciones_prestamo' => $request->observaciones_prestamo,
            'estado' => 'Activo',
            'usuario_despacha_id' => Auth::id(),
            'fecha_prestamo' => now(),
        ]);


        /*  if ($prestamos->isEmpty()) {
            return redirect()->route('prestamos.index')->with('error', 'No se encontraron préstamos pendientes para aprobar con esos criterios.');
        } */

        return redirect()->route('prestamos.index')->with('success', 'Solicitud aprobada correctamente.');
    }

    /**
     * Aprueba un préstamo solicitado.
     */
    public function aprobar(Prestamo $prestamo)
    {
        if ($prestamo->estado !== 'Pendiente') {
            return back()->with('error', 'Solo se pueden aprobar solicitudes pendientes.');
        }

        // Verificar disponibilidad del artículo
        if ($prestamo->articulo->estado !== 'disponible') {
            // Podríamos permitir aprobar incluso si no está disponible, pero es riesgoso.
            // Asumimos que si se solicita, estaba disponible o se espera disponibilidad.
            // Sin embargo, para consistencia, actualizamos el estado del artículo.
        }

        $prestamo->update([
            'estado' => 'Activo',
            'usuario_despacha_id' => Auth::user()->id,
            'fecha_prestamo' => now(), // Actualizamos la fecha de inicio al momento de aprobación real
        ]);

        $prestamo->articulo->update(['estado' => 'prestado']);

        return back()->with('success', 'Préstamo aprobado. El artículo ha sido marcado como prestado.');
    }

    /**
     * Registra la devolución de un préstamo.
     */
    public function devolver(Prestamo $prestamo)
    {
        if ($prestamo->estado !== 'Activo') {
            return back()->with('error', 'Este préstamo no está activo.');
        }

        $prestamo->update([
            'estado' => 'Devuelto',
            'usuario_recibe_id' => Auth::user()->id,
            'fecha_devolucion_real' => now(),
        ]);

        $prestamo->articulo->update(['estado' => 'disponible']);

        return back()->with('success', 'Devolución registrada. El artículo ahora está disponible.');
    }

    /**
     * Elimina un registro de préstamo (Solo admin).
     */
    public function destroy(Prestamo $prestamo)
    {
        if ($prestamo->estado === 'Activo') {
            return back()->with('error', 'No se puede eliminar un préstamo activo. Registre la devolución primero.');
        }

        // Si eliminamos una solicitud pendiente, aseguramos que el artículo quede disponible (ya debería estarlo, pero por seguridad)
        if ($prestamo->estado === 'Pendiente') {
            // No es necesario cambiar estado del articulo porque en pendiente sigue disponible,
            // pero si la lógica cambiara, aquí sería el lugar.
        }

        $prestamo->delete();

        return back()->with('success', 'Registro de préstamo eliminado correctamente.');
    }

    public function procesarSolicitud(Request $request)
    {
        $carrito = session()->get('carrito', []);

        if (empty($carrito)) {
            return redirect()->back()->with('error', 'La solicitud está vacía.');
        }

        DB::transaction(function () use ($carrito) {
            // 1. Crear el registro PADRE (la cabecera del préstamo)
            $padre = Prestamo::create([
                'usuario_solicitante_id' => Auth::id(),
                'fecha_prestamo' => now(),
                'fecha_devolucion_estimada' => now()->addHours(4), // Ejemplo
                'estado' => 'Pendiente',
                'id_padre' => null, // Es el jefe
                'articulo_id' => null
            ]);

            // 2. Crear los registros HIJOS (cada artículo)
            foreach ($carrito as $item) {
                Prestamo::create([
                    'id_padre' => $padre->id, // Vinculamos al padre
                    'articulo_id' => $item['id'],
                    'usuario_solicitante_id' => Auth::id(),
                    'estado' => 'Pendiente',
                    'fecha_prestamo' => $padre->fecha_prestamo,
                    'fecha_devolucion_estimada' => $padre->fecha_devolucion_estimada,
                ]);
            }
        });

        session()->forget('carrito'); // Limpiamos el carrito
        return redirect()->route('prestamos.mis-prestamos')->with('success', 'Solicitud enviada con éxito.');
    }
}
