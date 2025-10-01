<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuariosController extends Controller
{
    /**
     * Muestra una lista de todos los usuarios.
     */
    public function index()
    {
        // Buscamos todos los usuarios excepto el que está actualmente logueado
        // para evitar que el administrador se elimine a sí mismo por accidente.
        $usuarios = User::where('id', '!=', Auth::id())->latest()->paginate(10);

        return view('usuarios.index', compact('usuarios'));
    }

    /**
     * Elimina un usuario de la base de datos.
     */
    public function destroy(User $usuario)
    {
        // Medida de seguridad: No permitir eliminar al usuario principal (ID 1)
        if ($usuario->id === 1) {
            return back()->with('error', 'No se puede eliminar al administrador principal.');
        }

        $usuario->delete();

        return redirect()->route('usuarios.index')->with('success', 'Usuario eliminado exitosamente.');
    }

    // Aquí irían los otros métodos del CRUD (create, store, edit, update) si los necesitas.
}