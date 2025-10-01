{{-- resources/views/inventario/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Añadir Nuevo Artículo
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('inventario.store') }}" method="POST" class="space-y-6">
                        @csrf
                        {{-- Aquí incluiremos el formulario --}}
                        @include('inventario._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>