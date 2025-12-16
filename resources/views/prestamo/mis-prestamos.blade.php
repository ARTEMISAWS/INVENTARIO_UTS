<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Mis Préstamos
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    {{-- Mensaje de éxito cuando se solicita un préstamo --}}
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <h3 class="text-lg font-semibold mb-4">Historial de tus solicitudes</h3>

                    <div class="relative overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Artículo Solicitado</th>
                                    <th scope="col" class="px-6 py-3">Fecha de Solicitud</th>
                                    <th scope="col" class="px-6 py-3">Fecha de Devolución</th>
                                    <th scope="col" class="px-6 py-3">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($misPrestamos as $prestamo)
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                            {{ $prestamo->articulo->nombre }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $prestamo->fecha_prestamo->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{-- Solo muestra la fecha si el préstamo ha sido aprobado --}}
                                            {{ $prestamo->fecha_devolucion_estimada ? $prestamo->fecha_devolucion_estimada->format('d/m/Y') : 'Pendiente' }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{-- Usa colores para identificar el estado --}}
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($prestamo->estado == 'solicitado') bg-blue-100 text-blue-800 @endif
                                                @if($prestamo->estado == 'aprobado') bg-yellow-100 text-yellow-800 @endif
                                                @if($prestamo->estado == 'devuelto') bg-green-100 text-green-800 @endif
                                                @if($prestamo->estado == 'rechazado') bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($prestamo->estado) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                        <td colspan="4" class="px-6 py-4 text-center">
                                            Aún no has solicitado ningún préstamo.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    {{-- Paginación por si tienes muchos préstamos --}}
                    <div class="mt-4">
                        {{ $misPrestamos->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>