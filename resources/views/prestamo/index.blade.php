<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Catálogo de Artículos Disponibles
            </h2>
            
            {{-- Botón que lleva al historial de préstamos del usuario --}}
            <a href="{{ route('prestamos.mis-prestamos') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300">
                Ver Mis Préstamos
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Mensajes de notificación (éxito o error) --}}
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

            {{-- Grid para mostrar los artículos disponibles --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($articulosDisponibles as $articulo)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col">
                        <h3 class="font-semibold text-lg text-gray-900 dark:text-gray-100 mb-2">{{ $articulo->nombre }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 flex-grow">{{ Str::limit($articulo->descripcion, 100) }}</p>
                        <br>
                        <hr>
                        <h2 class="text-sm text-gray-600 dark:text-gray-400 flex-grow">Cantidad: {{ $articulo->cantidad }}</p>
                        
                        <div class="mt-4">
                            {{-- Formulario para enviar la solicitud de préstamo --}}
                            <form action="{{ route('prestamos.solicitar', $articulo) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                    Solicitar Préstamo
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-700 dark:text-gray-300 col-span-4">
                        No hay artículos disponibles para prestar en este momento.
                    </p>
                @endforelse
            </div>

            {{-- Paginación --}}
            <div class="mt-6">
                {{ $articulosDisponibles->links() }}
            </div>
        </div>
    </div>
</x-app-layout>