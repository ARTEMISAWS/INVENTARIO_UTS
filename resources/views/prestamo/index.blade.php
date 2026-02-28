<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Catálogo de Artículos
            </h2>
            @if(Auth::user()->role === 'usuario')
            <a href="{{ route('prestamos.mis-prestamos') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700">
                Ver Mis Préstamos
            </a>
            <a href="{{ route('carrito.index') }}" class="ml-2 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                Ver Solicitud ({{ count(session('carrito', [])) }})
            </a>
            @endif
        </div>
    </x-slot>

    <div class="py-1">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row gap-6">

            <div class="w-full md:w-1/4">
                <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg p-4">
                    <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-gray-100 border-b pb-2">Categorías</h3>

                    <div x-data="{ activeAccordion: null }">
                        @foreach($categorias as $categoria)
                        <div class="mb-2">
                            <button @click="activeAccordion = (activeAccordion === {{ $categoria->id }} ? null : {{ $categoria->id }})"
                                class="flex justify-between items-center w-full text-left px-3 py-2 bg-gray-100 dark:bg-gray-700 rounded-md hover:bg-indigo-50 dark:hover:bg-indigo-900 transition">
                                <span class="font-medium text-gray-700 dark:text-gray-200">
                                    {{ $categoria->nombre }}
                                    <span class="text-xs bg-gray-300 dark:bg-gray-600 px-2 py-0.5 rounded-full ml-1">{{ $categoria->subcategorias->sum('cantidad') }}</span>
                                </span>
                                <svg :class="activeAccordion === {{ $categoria->id }} ? 'rotate-180' : ''" class="w-4 h-4 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="activeAccordion === {{ $categoria->id }}" x-collapse class="pl-4 mt-2 space-y-1">
                                @foreach($categoria->subcategorias as $sub)
                                <a href="{{ route('prestamos.index', $sub->id) }}"
                                    class="flex justify-between items-center text-sm p-2 rounded hover:bg-indigo-100 dark:hover:bg-gray-600 {{ request('subcategoria') == $sub->id ? 'bg-indigo-200 dark:bg-indigo-800 font-bold' : 'text-gray-600 dark:text-gray-400' }}">
                                    <span>{{ $sub->nombre }}</span>
                                    <span class="text-xs text-gray-500">({{ $sub->cantidad }})</span>
                                </a>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <a href="{{ route('prestamos.index') }}" class="block mt-4 text-center text-xs text-indigo-600 hover:underline">Limpiar Filtros</a>
                </div>
            </div>

            <div class="w-full md:w-3/4">
                <div class="mb-6">
                    <form action="{{ route('prestamos.index') }}" method="GET" class="relative">
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Buscar por nombre, marca o modelo..."
                            class="w-full pl-10 pr-4 py-2 rounded-lg border border-gray-300 dark:bg-gray-700 dark:text-white focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                        <div class="absolute left-3 top-2.5 text-gray-400">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        @if(request('subcategoria'))
                        <input type="hidden" name="subcategoria" value="{{ request('subcategoria') }}">
                        @endif
                    </form>
                </div>


                @if(session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 shadow-sm" role="alert">
                    {{ session('success') }}
                </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($articulosDisponibles as $articulo)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl border border-gray-200 dark:border-gray-700 flex flex-col hover:shadow-2xl transition-shadow duration-300">
                        <div class="p-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">
                            <span class="text-[10px] uppercase tracking-widest font-bold text-indigo-500">{{ $articulo->marca }}</span>
                            <h3 class="font-bold text-gray-900 dark:text-gray-100 leading-tight h-12 overflow-hidden">{{ $articulo->nombre }}</h3>
                        </div>

                        <div class="pt-2 pl-4 flex-grow">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">
                                <strong>Categoria:</strong> {{ $articulo->subcategoria->categoria->nombre }}
                            </p>
                        </div>

                        <div class="pb-2 pl-4 flex-grow">
                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-2"><strong>Modelo:</strong> {{ $articulo->modelo ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-600 dark:text-gray-300 line-clamp-3">
                                {{ $articulo->descripcion }}
                            </p>
                        </div>


                        <div class="p-4 bg-gray-50 dark:bg-gray-900/30">
                            <form action="{{ route('carrito.agregar', $articulo->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-lg font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-150">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Agregar a Solicitud
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-full text-center py-12 bg-white dark:bg-gray-800 rounded-lg shadow">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No hay artículos</h3>
                        <p class="mt-1 text-sm text-gray-500">Selecciona otra subcategoría o intenta buscar de nuevo.</p>
                    </div>
                    @endforelse
                </div>

                <div class="mt-8">
                    {{ $articulosDisponibles->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
