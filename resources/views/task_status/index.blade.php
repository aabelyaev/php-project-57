<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Статусы</h1>
    </x-slot>

    <div class="grid max-w-screen-xl px-4 pt-20 pb-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12 lg:pt-28">
        <div class="grid col-span-full">
            <div>
                @auth
                    <a href="{{ route('task_statuses.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Создать статус</a>
                @endauth
            </div>

            <div style="margin-top: 20px" class="overflow-x-auto bg-white shadow rounded">
                <table class="min-w-full border divide-y divide-gray-200">
                    <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-sm text-left">ID</th>
                    <th class="px-4 py-2 text-sm text-left">Имя</th>
                    <th class="px-4 py-2 text-sm text-left">Дата создания</th>
                    @auth()
                        <th class="px-4 py-2 text-sm text-left">Действия</th>
                    @endauth
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @if(!empty($taskStatuses))
                    @foreach($taskStatuses as $taskStatus)
                        <tr class="border-b border-dashed text-left">
                            <td class="px-4 py-2 text-sm">{{ $taskStatus->id }}</td>
                            <td class="px-4 py-2 text-sm">{{ $taskStatus->name }}</td>
                            <td class="px-4 py-2 text-sm">{{ Carbon\Carbon::createFromDate($taskStatus->created_at)->format('d.m.Y') }}</td>
                            <td>
                                @auth
                                    <a rel="nofollow" data-confirm="Вы уверены?" data-method="delete" class="text-red-600 hover:text-red-900" href="{{ route('task_statuses.destroy', $taskStatus) }}">Удалить</a>
                                    <a class="text-blue-600 hover:text-blue-900" href="{{ route('task_statuses.edit', $taskStatus) }}">Изменить</a>
                                @endauth
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody></table>
            </div>
            <div class="p-4">
                {{ $taskStatuses->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
