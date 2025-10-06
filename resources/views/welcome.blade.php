<x-app-layout>
    <x-slot name="header">
        <h1 class="max-w-2xl mb-4 text-4xl font-extrabold leading-none tracking-tight md:text-5xl xl:text-6xl dark:text-white">
            {{ __('Привет от Хекслета!') }}
        </h1>

        <p class="max-w-2xl mb-6 font-light text-gray-500 lg:mb-8 md:text-lg lg:text-xl dark:text-gray-400">
            {{ __('Это простой менеджер задач на Laravel') }}
        </p>
    </x-slot>
</x-app-layout>
