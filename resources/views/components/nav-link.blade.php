@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block w-full pl-3 pr-4 py-2 border-l-4 border-indigo-400 text-left text-base font-medium text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-gray-700 focus:outline-none focus:ring-indigo-500 transition duration-150 ease-in-out'
            : 'block w-full pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 focus:outline-none focus:ring-indigo-500 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    <div class="flex items-center">
        @if (isset($icon))
            <span class="mr-3 h-6 w-6">
                {{ $icon }}
            </span>
        @endif

        <span>
            {{ $slot }}
        </span>
    </div>
</a>