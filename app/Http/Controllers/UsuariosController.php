<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Prestamo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UsuariosController extends Controller
{
    /**
     * Muestra una lista de todos los usuarios.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $userAuth = Auth::user();

        // 1. Iniciamos la consulta con el filtro base (siempre activos)
        $query = User::where('estado', 1);

        // 2. Aplicar lógica de Roles (Solo modificamos la consulta, NO paginamos aquí)
        if ($userAuth->role === 'admin') {
            // Admin: No ve admins ni superadmins
            $query->whereNotIn('role', ['admin', 'superadmin']);
        } elseif ($userAuth->role === 'superadmin') {
            // Superadmin: No se ve a sí mismo (u otros superadmins)
            $query->where('role', '!=', 'superadmin');
        }

        // 3. Filtro de Búsqueda
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                ->orWhere('cedula', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('role', 'like', "%{$search}%");
            });
        }

        // 4. Ordenar y Paginar (UNA SOLA VEZ al final)
        $usuarios = $query->orderBy('id', 'DESC')->paginate(5);
        
        // Mantener búsqueda en links de paginación
        $usuarios->appends(['search' => $search]);

        return view('usuarios.index', compact('usuarios', 'search'));
    }

    /**
     * Elimina un usuario de la base de datos.
     */
    public function destroy(User $usuario)
    {
        if($usuario->role === 'admin'){
            User::where('id', $usuario->id)->update(['estado' => 0]);
        }

        if($usuario->role === 'usuario'){
            // Antes de eliminar el usuario, verificar si tiene préstamos activos
            $prestamosActivos = Prestamo::where('usuario_solicitante_id', $usuario->id)
                                        ->where('estado', ['Activo'])
                                        ->count();

            if ($prestamosActivos > 0) {
                return redirect()->route('usuarios.index')->with('error', 'No se puede eliminar el usuario porque tiene préstamos activos.');
            }

            User::where('id', $usuario->id)->update(['estado' => 0]);
        }

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
        return redirect()->route('usuarios.index')
                         ->with('success', 'Usuario ' . $user->name . ' creado exitosamente.');
    }

    // Aquí irían los otros métodos del CRUD (create, store, edit, update) si los necesitas.
}