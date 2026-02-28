<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Registrar Devolución de Préstamo
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form action="{{ route('prestamos.guardarDevolucion') }}" method="POST">
                    @csrf
                    <input type="hidden" name="usuario_solicitante_id" value="{{ request('usuario_solicitante_id') }}">
                    <input type="hidden" name="id_prestamo" value="{{ request('id_prestamo') }}">

                    {{-- Encabezado con información general --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 border-b border-gray-200 dark:border-gray-700 pb-6">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Solicitante</p>
                            <p class="font-bold text-gray-900 dark:text-white text-lg">
                                {{ $misProductos->first()?->usuario_solicitante?->name ?? 'N/A' }}
                            </p>
                            <p class="text-xs text-gray-500">{{ $misProductos->first()?->usuario_solicitante?->email ?? '' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Recibido por</p>
                            <p class="font-bold text-gray-900 dark:text-white text-lg">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-indigo-600 block">Autenticado ahora</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Fecha y Hora de Recepción</p>
                            <p class="font-bold text-gray-900 dark:text-white text-lg">{{ now()->format('d/m/Y h:i A') }}</p>
                        </div>
                    </div>

                    {{-- Lista de Artículos a Devolver --}}
                    <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-200 mb-4">Artículos a Devolver</h3>
                    <div class="overflow-x-auto mb-8 border rounded-lg shadow-sm">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">Nombre</th>
                                    <th class="px-6 py-3">Marca</th>
                                    <th class="px-6 py-3">Modelo</th>
                                    <th class="px-6 py-3">Categoría</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($misProductos as $item)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        {{ $item->articulo->nombre }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $item->articulo->marca }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $item->articulo->modelo ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $item->articulo->subcategoria->categoria->nombre ?? 'Sin Categoría' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center">No se encontraron artículos para esta solicitud.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Campos de Entrada --}}
                    <div class="bg-gray-50 dark:bg-gray-700/30 p-6 rounded-lg border border-gray-100 dark:border-gray-700/50">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div>
                                <label for="fecha_devolucion_real" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Fecha y Hora de Recepción (Real)
                                </label>
                                <input type="datetime-local" id="fecha_devolucion_real" name="fecha_devolucion_real" required
                                    value="{{ now()->format('Y-m-d\TH:i') }}"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                                <p class="mt-1 text-xs text-gray-500">Puedes ajustar la hora si el equipo fue entregado antes de registrarlo en el sistema.</p>
                            </div>

                            <div>
                                <label for="observaciones_devolucion" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                    Novedades al recibir (Opcional)
                                </label>
                                <textarea id="observaciones_devolucion" name="observaciones_devolucion" rows="3"
                                    class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                                    placeholder="Indique si los equipos presentan algún daño o novedad..."></textarea>
                            </div>

                        </div>
                    </div>

                    {{-- Botones de Acción --}}
                    <div class="flex items-center justify-end mt-6 space-x-3">
                        <a href="{{ route('prestamos.index') }}" class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white font-medium text-sm px-4">
                            Cancelar
                        </a>
                        <button type="submit" class="text-white bg-indigo-700 hover:bg-indigo-800 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-800">
                            Confirmar Devolución
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>