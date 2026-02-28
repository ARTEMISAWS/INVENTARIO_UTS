<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Articulo;
use App\Models\Prestamo;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CarritoController extends Controller
{
    /**
     * Muestra el carrito de compras (solicitud de préstamos).
     */
    public function index()
    {
        $carrito = Session::get('carrito', []);

        // Obtenemos los detalles de los artículos en el carrito
        $articulosCarrito = [];
        if (!empty($carrito)) {
            $articulosCarrito = Articulo::whereIn('id', array_keys($carrito))->get();
        }

        return view('prestamo.carrito', compact('articulosCarrito', 'carrito'));
    }

    /**
     * Agrega un artículo al carrito.
     */
    public function agregar($articulo_id)
    {
        $data = Articulo::with('subcategoria.categoria')->where('id', $articulo_id)->first();

        // 1. Verificar si el usuario ya tiene un préstamo activo o pendiente
        $prestamoActivo = Prestamo::where('usuario_solicitante_id', Auth::id())
            ->whereIn('estado', ['Pendiente', 'Activo'])
            ->exists();

        if ($prestamoActivo) {
            return back()->with('error', 'No puedes realizar una nueva solicitud mientras tengas un préstamo activo o pendiente.');
        }


        // 2. Obtener el carrito actual
        $carrito = Session::get('carrito', []);
        // 3. Verificar si el artículo ya está en el carrito
        if (isset($carrito[$data->id])) {
            return back()->with('info', 'El artículo ya está en tu lista de solicitud.');
        }
        // 4. Verificar restricción: 1 artículo por categoría
        /*  foreach ($carrito as $id => $item) {
            // Nota: Guardamos categoria_id en el carrito para facilitar esta validación sin consultar DB
            if ($item['subcategoria_id'] == $data->subcategoria_id) {
                return back()->with('error', 'Solo puedes solicitar un artículo por categoría en cada préstamo. Ya tienes un artículo de la categoría: ' . $data->subcategoria->nombre);
            }
        } */

        // 5. Agregar al carrito
        $carrito[$data->id] = [
            'id' => $data->id,
            'nombre' => $data->nombre,
            'marca' => $data->marca,
            'modelo' => $data->modelo,
            'subcategoria_id' => $data->subcategoria_id,
            'categoria_id' => $data->subcategoria->categoria->id,
            'categoria_nombre' => $data->subcategoria->categoria->nombre,
            'subcategoria_nombre' => $data->subcategoria->nombre,
        ];
        Session::put('carrito', $carrito);
        return back()->with('success', 'Artículo agregado a la solicitud.');
    }

    /**
     * Elimina un artículo del carrito.
     */
    public function eliminar($id)
    {
        $carrito = Session::get('carrito', []);

        if (isset($carrito[$id])) {
            unset($carrito[$id]);
            Session::put('carrito', $carrito);
            return back()->with('success', 'Artículo eliminado de la solicitud.');
        }

        return back()->with('error', 'El artículo no estaba en la solicitud.');
    }

    /**
     * Vacía el carrito.
     */
    public function vaciar()
    {
        Session::forget('carrito');
        return back()->with('success', 'Solicitud vaciada correctamente.');
    }


    public function procesar()
    {
        $carrito = Session::get('carrito', []);

        if (empty($carrito)) {
            return back()->with('error', 'Tu lista de solicitud está vacía.');
        }

        // Verificación final de préstamo activo (por seguridad)
        // Agregamos whereNull('id_padre') para que busque solo en las cabeceras
        $prestamoActivo = Prestamo::where('usuario_solicitante_id', Auth::id())
            ->whereIn('estado', ['Pendiente', 'Activo'])
            ->whereNull('id_padre')
            ->exists();

        if ($prestamoActivo) {
            return redirect()->route('prestamos.mis-prestamos')
                ->with('error', 'No puedes realizar una nueva solicitud mientras tengas un préstamo activo o pendiente.');
        }

        DB::transaction(function () use ($carrito) {

            // 1. Crear la CABECERA del préstamo (El Padre)
            $padre = Prestamo::create([
                'articulo_id' => null, // La cabecera no lleva artículo
                'usuario_solicitante_id' => Auth::id(),
                'fecha_prestamo'         => now(),
                'fecha_devolucion_estimada'  => now()->addHours(2),
                'estado'  => 'Pendiente', // <--- SOLO EL PADRE LLEVA EL ESTADO
                'id_padre' => null,
            ]);

            // 2. Crear los DETALLES del préstamo (Los Hijos)
            foreach ($carrito as $id => $item) {
                Prestamo::create([
                    'articulo_id' => $id,
                    'usuario_solicitante_id' => Auth::id(),
                    'fecha_prestamo'         => now(),
                    'fecha_devolucion_estimada'  => now()->addHours(2),
                    'estado'  => null, // Dejamos el estado nulo en los hijos
                    'id_padre' => $padre->id, // Los amarramos al padre creado arriba
                ]);
            }
        });

        // Vaciar carrito
        Session::forget('carrito');

        return redirect()->route('prestamos.mis-prestamos')->with('success', 'Solicitud enviada correctamente. Espera la aprobación del administrador.');
    }
}
