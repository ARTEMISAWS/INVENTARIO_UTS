<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Resumen de Solicitud
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if(Session::has('info'))
                    <div class="bg-blue-100 border-l-4 border-blue-500 text-blue-700 p-4 mb-4" role="alert">
                        {{ Session::get('info') }}
                    </div>
                    @endif

                    @if(Session::has('error'))
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                        {{ Session::get('error') }}
                    </div>
                    @endif

                    @if(empty($carrito))
                    <div class="text-center py-8">
                        <p class="text-gray-500">No tienes artículos en tu solicitud.</p>
                        <a href="{{ route('prestamos.index') }}" class="mt-4 inline-block text-indigo-600 hover:underline">Volver al catálogo</a>
                    </div>
                    @else
                    <div class="overflow-x-auto mb-6">
                        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">Artículo</th>
                                    <th class="px-6 py-3">Categoría</th>
                                    <th class="px-6 py-3">Marca/Modelo</th>
                                    <th class="px-6 py-3">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($carrito as $id => $item)
                                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                                    <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">
                                        {{ $item['nombre'] }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $item['categoria_nombre'] }}
                                    </td>
                                    <td class="px-6 py-4">
                                        {{ $item['marca'] }} / {{ $item['modelo'] }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <form action="{{ route('carrito.eliminar', $id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline font-bold">Quitar</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="flex justify-between items-center border-t pt-4">
                        <form action="{{ route('carrito.vaciar') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-red-600 font-medium">Vaciar lista</button>
                        </form>

                        <div class="space-x-4">
                            <a href="{{ route('prestamos.index') }}" class="text-indigo-600 hover:underline">Seguir agregando</a>

                            <form action="{{ route('carrito.procesar') }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-bold shadow-lg transform transition hover:scale-105">
                                    Confirmar Solicitud
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>