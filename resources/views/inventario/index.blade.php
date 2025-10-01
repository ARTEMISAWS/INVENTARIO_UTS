{{-- resources/views/inventario/index.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Gestión de Inventario
            </h2>
            <a href="{{ route('inventario.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                Añadir Artículo
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- Mensajes de notificación --}}
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif
                     @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            {{-- ... (código de la tabla aquí) ... --}}
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    
                                    <th scope="col" class="px-6 py-3">Código</th>
                                    <th scope="col" class="px-6 py-3">Nombre</th>
                                    <th scope="col" class="px-6 py-3">Calcomanía</th>
                                    <th scope="col" class="px-6 py-3">Descripción</th>
                                    <th scope="col" class="px-6 py-3">Categoría</th>
                                    <th scope="col" class="px-6 py-3">Estado</th>
                                    <th scope="col" class="px-6 py-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($articulos as $articulo)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="px-6 py-4">{{ $articulo->codigo_uts }}</td>
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $articulo->nombre }}</td>
                                        <td class="px-6 py-4">{{ $articulo->calcomania }}</td>
                                        <td class="px-6 py-4">{{ $articulo->descripcion }}</td>
                                        <td class="px-6 py-4">{{ $articulo->categoria->nombre }}</td>
                                        <td class="px-6 py-4">{{ $articulo->estado }}</td>
                                        <td class="px-6 py-4 flex items-center space-x-2">
                                            <a href="{{ route('inventario.edit', $articulo) }}" class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                                </svg>
                                            </a>
                                            <form action="{{ route('inventario.destroy', $articulo->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este artículo?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center">No hay artículos registrados.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">{{ $articulos->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>