<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Gestión de Préstamos
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Mensajes --}}
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                {{-- Buscador --}}
                <div class="mb-4">
                    <form action="{{ route('prestamos.index') }}" method="GET" class="flex">
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Buscar por artículo o usuario..." class="rounded-l-md border-gray-300 dark:bg-gray-700 w-full">
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-r-md">Buscar</button>
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th class="px-6 py-3">Artículo</th>
                                <th class="px-6 py-3">Solicitante</th>
                                <th class="px-6 py-3">Estado</th>
                                <th class="px-6 py-3">Fecha Solicitud</th>
                                <th class="px-6 py-3">Fecha devolución</th>
                                <th class="px-6 py-3">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($prestamos as $prestamo)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        {{ $prestamo->articulo->nombre }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $prestamo->usuario_solicitante->name }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded-full text-xs 
                                            {{ $prestamo->estado == 'Pendiente' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                            {{ $prestamo->estado == 'Activo' ? 'bg-blue-100 text-blue-800' : '' }}
                                            {{ $prestamo->estado == 'Devuelto' ? 'bg-green-100 text-green-800' : '' }}">
                                            {{ ucfirst($prestamo->estado) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $prestamo->fecha_prestamo->format('d/m/Y H:i') }}
                                    </td>

                                    <td class="px-6 py-4">
                                        {{ $prestamo->fecha_devolucion_real == null ? "" : $prestamo->fecha_devolucion_real->format('d/m/Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 flex space-x-2">
                                        @if($prestamo->estado === 'Pendiente')
                                            <form action="{{ route('prestamos.aprobar', $prestamo) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button class="text-green-600 hover:underline font-bold">Aprobar</button>
                                            </form>
                                        @endif

                                        @if(Auth::user()->role === 'admin' && $prestamo->estado === 'Activo')
                                            <form action="{{ route('prestamos.devolver', $prestamo) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button class="text-indigo-600 hover:underline font-bold">Recibir</button>
                                            </form>
                                        @endif

                                        @if(Auth::user()->role === 'admin')
                                            <form action="{{ route('prestamos.destroy', $prestamo) }}" method="POST" onsubmit="return confirm('¿Eliminar registro?')">
                                                @csrf @method('DELETE')
                                                <button class="text-red-600 hover:underline">Eliminar</button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center">No hay registros de préstamos.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $prestamos->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>