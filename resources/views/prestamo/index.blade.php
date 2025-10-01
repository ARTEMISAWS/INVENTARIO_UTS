<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Gestión de Préstamos
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- (Aquí puedes poner mensajes de session('success')) --}}

                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">Artículo</th>
                                    <th class="px-6 py-3">Solicitante</th>
                                    <th class="px-6 py-3">Estado</th>
                                    <th class="px-6 py-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($prestamos as $prestamo)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="px-6 py-4">{{ $prestamo->articulo->nombre }}</td>
                                        <td class="px-6 py-4">{{ $prestamo->usuario_solicitante->nombre }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($prestamo->estado == 'solicitado') bg-blue-100 text-blue-800 @endif
                                                @if($prestamo->estado == 'aprobado') bg-yellow-100 text-yellow-800 @endif
                                                @if($prestamo->estado == 'devuelto') bg-green-100 text-green-800 @endif">
                                                {{ $prestamo->estado }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 flex items-center space-x-2">
                                            @if($prestamo->estado == 'solicitado')
                                                <form action="{{ route('prestamos.aprobar', $prestamo) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="date" name="fecha_devolucion_estimada" required class="text-sm rounded-md dark:bg-gray-900">
                                                    <button type="submit" class="font-medium text-green-600 hover:underline">Aprobar</button>
                                                </form>
                                                <form action="{{ route('prestamos.destroy', $prestamo) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="font-medium text-red-600 hover:underline">Rechazar</button>
                                                </form>
                                            @elseif($prestamo->estado == 'aprobado')
                                                <form action="{{ route('prestamos.devolver', $prestamo) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="font-medium text-blue-600 hover:underline">Registrar Devolución</button>
                                                </form>
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center py-4">No hay préstamos para gestionar.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>