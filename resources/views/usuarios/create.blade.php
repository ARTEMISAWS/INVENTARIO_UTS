<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Crear Nuevo Usuario
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:p-8 bg-white dark:bg-gray-800">
                    
                    {{-- Formulario que apunta al método store del resource --}}
                    <form method="POST" action="{{ route('usuariosadmin.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="name" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Nombre</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
                            @error('name')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="cedula" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Cédula</label>
                            <input id="cedula" type="text" name="cedula" value="{{ old('cedula') }}" required
                                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
                            @error('cedula')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
                            @error('email')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Contraseña</label>
                            <input id="password" type="password" name="password" required
                                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
                            @error('password')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Confirmar Contraseña</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500" />
                        </div>
                        
                        <div class="mb-6">
                            <label for="role" class="block font-medium text-sm text-gray-700 dark:text-gray-300">Asignar Rol</label>
                            <select id="role" name="role" required
                                class="mt-1 block w-full rounded-md shadow-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="" disabled selected>Seleccione un Rol</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                                <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>Super Administrador</option>
                                <option value="invitado" {{ old('role') == 'invitado' ? 'selected' : '' }}>Invitado</option>
                                {{-- Agrega más roles según necesites --}}
                            </select>
                            @error('role')
                                <p class="text-sm text-red-600 dark:text-red-400 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('usuarios.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-400 focus:outline-none focus:border-gray-600 focus:ring disabled:opacity-25 transition mr-4">
                                Cancelar
                            </a>
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:outline-none focus:border-indigo-700 focus:ring disabled:opacity-25 transition">
                                Crear Usuario
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>