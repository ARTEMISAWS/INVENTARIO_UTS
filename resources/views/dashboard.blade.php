<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                <div class="bg-white dark:bg-slate-800 p-6 rounded-lg shadow-md flex flex-col items-center text-center">
                    <div class="mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-gray-500 dark:text-gray-300">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Total de Artículos</h3>
                    <p class="mt-2 text-4xl font-bold text-gray-900 dark:text-white">150</p>
                </div>

                <div class="bg-white dark:bg-slate-800 p-6 rounded-lg shadow-md flex flex-col items-center text-center">
                    <div class="mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-gray-500 dark:text-gray-300">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Artículos Prestados</h3>
                    <p class="mt-2 text-4xl font-bold text-gray-900 dark:text-white">150</p>
                </div>

                <div class="bg-white dark:bg-slate-800 p-6 rounded-lg shadow-md flex flex-col items-center text-center">
                    <div class="mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-gray-500 dark:text-gray-300">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Artículos Disponibles</h3>
                    <p class="mt-2 text-4xl font-bold text-gray-900 dark:text-white">150</p>
                </div>

                <div class="bg-white dark:bg-slate-800 p-6 rounded-lg shadow-md flex flex-col items-center text-center">
                    <div class="mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-gray-500 dark:text-gray-300">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Artículos Dañados</h3>
                    <p class="mt-2 text-4xl font-bold text-gray-900 dark:text-white">150</p>
                </div>

            </div>
            </div>
    </div>
</x-app-layout>