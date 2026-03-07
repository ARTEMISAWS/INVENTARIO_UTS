<?php

namespace App\Http\Controllers;

use App\Models\Subcategoria;
use App\Models\Categoria;
use Illuminate\Http\Request;

class SubCategoriaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $subcategorias = Subcategoria::with('categoria')
            ->when($search, function ($query) use ($search) {
                $query->where('nombre', 'like', "%{$search}%")
                      ->orWhereHas('categoria', function ($q) use ($search) {
                          $q->where('nombre', 'like', "%{$search}%");
                      });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('subcategorias.index', compact('subcategorias', 'search'));
    }

    public function create()
    {
        $categorias = Categoria::all();
        return view('subcategorias.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'       => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'descripcion'  => 'nullable|string',
            'cantidad'     => 'nullable|integer|default:0',
        ]);

        Subcategoria::create($request->all());
        return redirect()->route('subcategorias.index')->with('success', 'Subcategoría creada exitosamente.');
    }

    public function edit(Subcategoria $subcategoria)
    {
        $categorias = Categoria::all();
        return view('subcategorias.edit', compact('subcategoria', 'categorias'));
    }

    public function update(Request $request, Subcategoria $subcategoria)
    {
        $request->validate([
            'nombre'       => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'descripcion'  => 'nullable|string',
            'cantidad'     => 'nullable|integer',
        ]);

        $subcategoria->update($request->all());
        return redirect()->route('subcategorias.index')->with('success', 'Subcategoría actualizada exitosamente.');
    }

    public function destroy(Subcategoria $subcategoria)
    {
        if ($subcategoria->articulos()->exists()) {
            return back()->with('error', 'No se puede eliminar la subcategoría porque tiene artículos asociados.');
        }

        $subcategoria->delete();
        return redirect()->route('subcategorias.index')->with('success', 'Subcategoría eliminada exitosamente.');
    }
}
