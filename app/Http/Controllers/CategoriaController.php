<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $categorias = Categoria::when($search, function ($query) use ($search) {
                $query->where('nombre', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('categorias.index', compact('categorias', 'search'));
    }

    public function create()
    {
        return view('categorias.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'      => 'required|string|max:255|unique:categorias',
            'cantidad'    => 'nullable|integer|default:0',
        ]);

        Categoria::create($request->all());
        return redirect()->route('categorias.index')->with('success', 'Categoría creada exitosamente.');
    }

    public function edit(Categoria $categoria)
    {
        return view('categorias.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria)
    {
        $request->validate([
            'nombre'      => 'required|string|max:255|unique:categorias,nombre,' . $categoria->id,
            'cantidad'    => 'nullable|integer',
        ]);

        $categoria->update($request->all());
        return redirect()->route('categorias.index')->with('success', 'Categoría actualizada exitosamente.');
    }

    public function destroy(Categoria $categoria)
    {
        if ($categoria->subcategorias()->exists()) {
            return back()->with('error', 'No se puede eliminar la categoría porque tiene subcategorías asociadas.');
        }

        $categoria->delete();
        return redirect()->route('categorias.index')->with('success', 'Categoría eliminada exitosamente.');
    }
}
