<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UsuariosController extends Controller
{
    /**
     * Muestra una lista de todos los usuarios.
     */
    public function index()
    {
        // Buscamos todos los usuarios excepto el que está actualmente logueado
        // para evitar que el administrador se elimine a sí mismo por accidente.
        
        if(Auth::user()->role === 'superadmin'){
           $usuarios = User::paginate(5);
        }
        
        if(Auth::user()->role === 'admin'){
            $usuarios = User::where('role', '!=', 'admin')->where('role', '!=', 'superadmin')->latest()->paginate(5);
        }

        return view('usuarios.index', compact('usuarios'));
    }


    /* public function index(Request $request)
    {
        // 1. Obtener el término de búsqueda de la URL
        $search = $request->get('search');

        // 2. Iniciar la consulta base (Query Builder)
        $query = User::query();

        // 3. Aplicar la lógica de Roles
        if (Auth::user()->role === 'admin') {
            // Un administrador NO ve a otros administradores o superadministradores
            $query->where('role', '!=', 'admin')
                  ->where('role', '!=', 'superadmin');
        } 
        // Nota: Si es 'superadmin', ve todos los usuarios (no se aplica un where inicial).

        // 4. Aplicar el Filtro de Búsqueda Global (whereLike)
        if ($search) {
            $query->where(function ($q) use ($search) {
                // El método 'where(function)' permite agrupar condiciones (OR)
                // para buscar el texto en cualquiera de los campos.
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('cedula', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('role', 'like', '%' . $search . '%');
            });
        }

        // 5. Ordenar y Paginar
        $usuarios = $query->latest()->paginate(5);
        
        // Mantener el término de búsqueda en la paginación
        $usuarios->appends(['search' => $search]);

        return view('usuarios.index', compact('usuarios', 'search'));
    } */

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

    public function create()
    {
        // Puedes pasar roles aquí si los tienes definidos
        // $roles = Role::pluck('name', 'id'); 
        
        return view('usuarios.create');
    }

    public function store(Request $request)
    {
        // 1. Validación de los datos
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'cedula' => ['required', 'string', 'max:20', 'unique:users'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'string', 'in:admin,superadmin,invitado'], // Ajusta los roles según tu app
        ]);

        // 2. Creación del Usuario
        $user = User::create([
            'name' => $request->name,
            'cedula' => $request->cedula,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // Asegúrate de que este campo exista en tu tabla 'users'
            'role' => $request->role, 
        ]);

        // Si usas Spatie/Permission:
        // $user->assignRole($request->role);


        // 3. Redirección
        return redirect()->route('usuariosadmin.index')
                         ->with('success', 'Usuario ' . $user->name . ' creado exitosamente.');
    }

    // Aquí irían los otros métodos del CRUD (create, store, edit, update) si los necesitas.
}