<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use App\Models\Categoria;
use App\Models\Ubicacion;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    /**
     * Muestra una lista de todos los artículos del inventario.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $articulos = Articulo::with('categoria', 'ubicacion')
            ->where('estado', 'Disponible') // Mantenemos tu filtro base
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('nombre', 'like', "%{$search}%")
                    ->orWhere('codigo_uts', 'like', "%{$search}%")
                    ->orWhere('calcomania', 'like', "%{$search}%")
                    ->orWhere('descripcion', 'like', "%{$search}%")
                    // Para buscar en la relación de categoría
                    ->orWhereHas('categoria', function ($subQ) use ($search) {
                        $subQ->where('nombre', 'like', "%{$search}%");
                    });
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString(); // Esto mantiene el parámetro 'search' en los links de paginación

        return view('inventario.index', compact('articulos', 'search'));
    }

    /**
     * Muestra el formulario para crear un nuevo artículo.
     */
    public function create()
    {
        $categorias = Categoria::all();
        $ubicaciones = Ubicacion::all();
        return view('inventario.create', compact('categorias', 'ubicaciones'));
    }

    /**
     * Guarda un nuevo artículo en la base de datos.
     */
    public function store(Request $request)
    {
        $request->validate([
            'codigo_uts'  => 'required|string|max:255|unique:articulos',
            'nombre'      => 'required|string|max:255',
            'descripcion' => 'required|string',
            'marca'       => 'required|nullable|string|max:255',
            'modelo'      => 'required|nullable|string|max:255',
            'numero_serie' => 'required|nullable|string|max:255|unique:articulos',
            'calcomania'   => 'required|nullable|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'ubicacion_id' => 'required|exists:ubicaciones,id',
        ]);

        Articulo::create($request->all());

        return redirect()->route('inventario.index')->with('success', 'Artículo creado exitosamente.');
    }

    /**
     * Muestra el formulario para editar un artículo existente.
     * Gracias al Route Model Binding, Laravel nos entrega el objeto $articulo automáticamente.
     */
    public function edit($idArticulo)
    {
        $articulo = Articulo::find($idArticulo);
        $categorias = Categoria::all();
        $ubicaciones = Ubicacion::all();
        return view('inventario.edit', compact('articulo', 'categorias', 'ubicaciones'));
    }

    /**
     * Actualiza un artículo en la base de datos.
     */
    public function update(Request $request, $idArticulo)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'codigo_uts' => 'required|string|max:255|unique:articulos,codigo_uts,' . $idArticulo,
            'descripcion' => 'required|string',
            'marca' => 'nullable|string|max:255',
            'modelo' => 'nullable|string|max:255',
            'numero_serie' => 'nullable|string|max:255|unique:articulos,numero_serie,' . $idArticulo,
            'categoria_id' => 'required|exists:categorias,id',
            'ubicacion_id' => 'required|exists:ubicaciones,id',
            'estado' => 'required|in:disponible,prestado,en_mantenimiento,de_baja',
        ]);
        $articulo = Articulo::find($idArticulo);
        $articulo->fill($request->all());
        $articulo->save();
        return redirect()->route('inventario.index')->with('success', 'Artículo actualizado exitosamente.');
    }

    /**
     * Elimina un artículo de la base de datos.
     */
    public function destroy($idArticulo)
    {
        $articulo = Articulo::find($idArticulo);

        // Opcional: Añadir lógica para no permitir borrar si está prestado.
        if ($articulo->estado === 'prestado') {
            return back()->with('error', 'No se puede eliminar un artículo que está actualmente prestado.');
        }

        if ($articulo->estado === 'de_baja') {
            $articulo->dado_baja = 2;
            $articulo->save();
        }

        return redirect()->route('inventario.index')->with('success', 'Artículo eliminado exitosamente.');
    }
}