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

    <div class="py-1">
        <div class="sm:px-6 lg:px-8">
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

                    <div class="mb-6">
                        <form action="{{ route('inventario.index') }}" method="GET" class="flex flex-col md:flex-row md:items-center justify-between gap-4">

                            {{-- Buscador --}}
                            <div class="flex items-center w-full md:w-auto">
                                <label for="simple-search" class="sr-only">Buscar</label>
                                <div class="relative w-full md:w-96">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                        </svg>
                                    </div>
                                    <input type="text" name="search" id="simple-search" value="{{ $search }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-indigo-500 dark:focus:border-indigo-500" placeholder="Buscar por código, nombre, categoría...">
                                </div>
                                <button type="submit" class="p-2.5 ml-2 text-sm font-medium text-white bg-indigo-600 rounded-lg border border-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300">
                                    <span>Buscar</span>
                                </button>
                                @if(request('search') || request('estado'))
                                <a href="{{ route('inventario.index') }}" class="p-2.5 ml-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 text-center">
                                    Limpiar
                                </a>
                                @endif
                            </div>

                            {{-- Filtro de Estado (Radio Buttons) --}}
                            <div class="flex flex-wrap items-center gap-4 bg-gray-50 dark:bg-gray-700/50 p-2 rounded-lg border border-gray-200 dark:border-gray-600">
                                <span class="text-xs font-bold uppercase text-gray-500 truncate mr-1">Filtrar por:</span>

                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" name="estado" value="" class="hidden peer" onchange="this.form.submit()" {{ request('estado') == '' ? 'checked' : '' }}>
                                    <span class="px-3 py-1 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-full peer-checked:bg-gray-800 peer-checked:text-white peer-checked:border-transparent transition-all hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:peer-checked:bg-indigo-600">
                                        Todos
                                    </span>
                                </label>

                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" name="estado" value="disponible" class="hidden peer" onchange="this.form.submit()" {{ request('estado') == 'disponible' ? 'checked' : '' }}>
                                    <span class="px-3 py-1 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-full peer-checked:bg-green-600 peer-checked:text-white peer-checked:border-transparent transition-all hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:peer-checked:bg-green-600">
                                        Disponible
                                    </span>
                                </label>

                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" name="estado" value="prestado" class="hidden peer" onchange="this.form.submit()" {{ request('estado') == 'prestado' ? 'checked' : '' }}>
                                    <span class="px-3 py-1 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-full peer-checked:bg-blue-600 peer-checked:text-white peer-checked:border-transparent transition-all hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:peer-checked:bg-blue-600">
                                        Prestado
                                    </span>
                                </label>

                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" name="estado" value="mantenimiento" class="hidden peer" onchange="this.form.submit()" {{ request('estado') == 'mantenimiento' ? 'checked' : '' }}>
                                    <span class="px-3 py-1 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-full peer-checked:bg-yellow-500 peer-checked:text-white peer-checked:border-transparent transition-all hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:peer-checked:bg-yellow-500">
                                        Mantenimiento
                                    </span>
                                </label>

                                <label class="inline-flex items-center cursor-pointer">
                                    <input type="radio" name="estado" value="inactivo" class="hidden peer" onchange="this.form.submit()" {{ request('estado') == "inactivo" ? 'checked' : '' }}>
                                    <span class="px-3 py-1 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-full peer-checked:bg-red-600 peer-checked:text-white peer-checked:border-transparent transition-all hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:peer-checked:bg-red-600">
                                        Inactivo
                                    </span>
                                </label>
                            </div>
                        </form>
                    </div>

                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Código</th>
                                    <th scope="col" class="px-6 py-3">Nombre</th>
                                    <th scope="col" class="px-6 py-3">Calcomanía</th>
                                    <th scope="col" class="px-6 py-3">Descripción</th>
                                    <th scope="col" class="px-6 py-3">Categoría</th>
                                    <th scope="col" class="px-6 py-3">Ubicación</th>
                                    <th scope="col" class="px-6 py-3">Estado</th>
                                    <th scope="col" class="px-6 py-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($articulos as $articulo)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="px-6 py-4">{{ $articulo->codigo_uts }}</td>
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white"> {{ $articulo->nombre }} </td>
                                    <td class="px-6 py-4">{{ $articulo->calcomania }} </td>
                                    <td class="px-6 py-4">{{ $articulo->descripcion }}</td>
                                    <td class="px-6 py-4">
                                        {{ $articulo->subcategoria?->categoria?->nombre ?? 'N/A' }}
                                        @if($articulo->subcategoria)
                                        > {{ $articulo->subcategoria->nombre }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $articulo->ubicacion->nombre ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @switch($articulo->estado)
                                        @case('disponible')
                                        <span class="px-2 py-1 font-semibold leading-tight text-green-700 bg-green-100 rounded-full dark:bg-green-700 dark:text-green-100">
                                            Disponible
                                        </span>
                                        @break
                                        @case('prestado')
                                        <span class="px-2 py-1 font-semibold leading-tight text-yellow-700 bg-yellow-100 rounded-full dark:bg-yellow-700 dark:text-yellow-100">
                                            Prestado
                                        </span>
                                        @break
                                        @case('en_mantenimiento')
                                        <span class="px-2 py-1 font-semibold leading-tight text-red-700 bg-red-100 rounded-full dark:bg-red-700 dark:text-red-100">
                                            En Mantenimiento
                                        </span>
                                        @break
                                        @case('inactivo')
                                        <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-100">
                                            Inactivo
                                        </span>
                                        @break
                                        @default
                                        <span class="px-2 py-1 font-semibold leading-tight text-gray-700 bg-gray-100 rounded-full dark:bg-gray-700 dark:text-gray-100">
                                            {{ $articulo->estado }}
                                        </span>
                                        @endswitch
                                    </td>
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