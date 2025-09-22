<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Статусы') }}
        </h2>
    </x-slot>

    <div class="flex justify-center">
        <div class="mt-16 bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-[40%] overflow-hidden transition-all duration-300 hover:shadow-3xl animate-fade-in">
            <div class="p-6">
                <div class="flex justify-between">
                    <div class="w-[80%] flex justify-between items-center space-x-4 mb-2 p-3 rounded-lg">
                        <div class="flex justify-start">
                            <div class="mr-2">
                                <h3 class="text-lg font-semibold text-indigo-800 dark:text-white">ID</h3>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-indigo-800 dark:text-white">Имя</h3>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-indigo-800 dark:text-white">Дата создания</h3>
                        </div>
                    </div>

                    @auth()
                        <div class="w-[18%] h-auto text-center items-center space-x-4 mb-2 p-3">
                            <h3 class="text-lg font-semibold text-indigo-800 dark:text-white">Действия</h3>
                        </div>
                    @endauth
                </div>
                @if(!empty($taskStatuses))
                    @foreach($taskStatuses as $taskStatus)
                        <div class="flex justify-between">
                            <div class="w-[80%] flex justify-between items-center space-x-4 mb-2 p-3 bg-indigo-50 dark:bg-gray-700 rounded-lg transition-all duration-300 hover:bg-indigo-100 dark:hover:bg-gray-600">
                                <div class="flex justify-start">
                                    <div class="mr-5">
                                        <p class="text-sm mt-1.5 text-gray-600 font-bold dark:text-gray-300">{{ $taskStatus->id }}</p>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-indigo-800 dark:text-white">{{ $taskStatus->name }}</h3>
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm mt-1 text-gray-600 font-bold dark:text-gray-300">{{ Carbon\Carbon::createFromDate($taskStatus->created_at)->format('d.m.Y') }}</p>
                                </div>
                            </div>

                            @auth()
                                <div class="flex w-[18%] h-auto items-center mb-2">
                                    <div class="flex justify-between">
                                        <a href="{{ route('task_statuses.edit', $taskStatus) }}"
                                           class="w-[48%] inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150">
                                            <svg class="w-[50%] mx-auto fill-indigo-800"
                                                 xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512">
                                                <!--!Font Awesome Free v7.0.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                                <path d="M352.9 21.2L308 66.1 445.9 204 490.8 159.1C504.4 145.6 512 127.2 512 108s-7.6-37.6-21.2-51.1L455.1 21.2C441.6 7.6 423.2 0 404 0s-37.6 7.6-51.1 21.2zM274.1 100L58.9 315.1c-10.7 10.7-18.5 24.1-22.6 38.7L.9 481.6c-2.3 8.3 0 17.3 6.2 23.4s15.1 8.5 23.4 6.2l127.8-35.5c14.6-4.1 27.9-11.8 38.7-22.6L412 237.9 274.1 100z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('task_statuses.destroy', $taskStatus) }}"
                                           data-confirm="{{ __('Вы уверенны?') }}" data-method="delete" rel="nofollow"
                                           class="w-[48%] inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                            <svg class="w-[50%] mx-auto fill-white" xmlns="http://www.w3.org/2000/svg"
                                                 viewBox="0 0 448 512">
                                                <!--!Font Awesome Free v7.0.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                                                <path d="M136.7 5.9L128 32 32 32C14.3 32 0 46.3 0 64S14.3 96 32 96l384 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-96 0-8.7-26.1C306.9-7.2 294.7-16 280.9-16L167.1-16c-13.8 0-26 8.8-30.4 21.9zM416 144L32 144 53.1 467.1C54.7 492.4 75.7 512 101 512L347 512c25.3 0 46.3-19.6 47.9-44.9L416 144z"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            @endauth
                        </div>
                    @endforeach
                @endif
            </div>

            <div class="p-6">
                {{ $taskStatuses->links() }}
            </div>
        </div>

        @auth
            <a href="{{ route('task_statuses.create') }}"
               class="flex w-[5%] items-center mt-16 ml-4 bg-white dark:bg-gray-800 rounded-xl shadow-2xl overflow-hidden transition ease-in-out duration-150 hover:shadow-3xl animate-fade-in tracking-widest hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25">
                <svg class="w-[40%] mx-auto fill-indigo-800" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                    <!--!Font Awesome Free v7.0.1 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license/free Copyright 2025 Fonticons, Inc.-->
                    <path d="M256 64c0-17.7-14.3-32-32-32s-32 14.3-32 32l0 160-160 0c-17.7 0-32 14.3-32 32s14.3 32 32 32l160 0 0 160c0 17.7 14.3 32 32 32s32-14.3 32-32l0-160 160 0c17.7 0 32-14.3 32-32s-14.3-32-32-32l-160 0 0-160z"/>
                </svg>
            </a>
        @endauth
    </div>
</x-app-layout>
