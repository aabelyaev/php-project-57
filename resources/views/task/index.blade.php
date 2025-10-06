<x-app-layout>
    <x-slot name="header">
        <h1 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Задачи</h1>
    </x-slot>

    <div class="grid max-w-screen-xl px-4 pt-20 pb-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12 lg:pt-28">
        <div class="grid col-span-full">
            <div class="w-full flex items-center">
                <div>
                    <form method="GET" action="{{ route('tasks.index') }}">
                        <div class="flex">
                            <select class="rounded border-gray-300" name="filter[status_id]" id="filter[status_id]">
                                <option value="">Статус</option>
                                @foreach($statuses as $status)
                                    @if(request()->input('filter.status_id') === (string) $status->id)
                                        <option selected="selected"
                                                value="{{ $status->id }}">{{ $status->name }}</option>
                                    @else
                                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <select class="rounded border-gray-300" name="filter[created_by_id]"
                                    id="filter[created_by_id]">
                                <option value="">Автор</option>
                                @foreach($users as $user)
                                    @if(request()->input('filter.created_by_id') === (string) $user->id)
                                        <option selected="selected" value="{{ $user->id }}">{{ $user->name }}</option>
                                    @else
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <select class="rounded border-gray-300" name="filter[assigned_to_id]"
                                    id="filter[assigned_to_id]">
                                <option value="">Исполнитель</option>
                                @foreach($users as $user)
                                    @if(request()->input('filter.assigned_to_id') === (string) $user->id)
                                        <option selected="selected" value="{{ $user->id }}">{{ $user->name }}</option>
                                    @else
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2"
                                    type="submit">Применить</button>

                        </div>
                    </form>
                </div>

                <div class="ml-auto">
                    @auth
                        <a href="{{ route('tasks.create') }}"
                           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded ml-2">Создать задачу</a>
                    @endauth
                </div>
            </div>
            <div class="overflow-x-auto bg-white shadow rounded">
            <table class="min-w-full border divide-y divide-gray-200">
                <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-sm text-left">ID</th>
                    <th class="px-4 py-2 text-sm text-left">Статус</th>
                    <th class="px-4 py-2 text-sm text-left">Имя</th>
                    <th class="px-4 py-2 text-sm text-left">Автор</th>
                    <th class="px-4 py-2 text-sm text-left">Исполнитель</th>
                    <th class="px-4 py-2 text-sm text-left">Дата создания</th>
                    @auth()
                        <th>Действия</th>
                    @endauth
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @if(!empty($tasks))
                    @foreach($tasks as $task)
                        <tr>
                            <td class="px-4 py-2 text-sm">{{ $task->id }}</td>
                            <td class="px-4 py-2 text-sm">{{ $task->status->name }}</td>
                            <td class="px-4 py-2 text-sm text-blue-600 hover:underline">
                                <a href="{{ route('tasks.show', $task) }}">
                                    {{ $task->name }}
                                </a>
                            </td>
                            <td class="px-4 py-2 text-sm">{{ $task->createdBy->name }}</td>
                            <td class="px-4 py-2 text-sm">{{ $task->assignedTo->name ?? '' }}</td>
                            <td class="px-4 py-2 text-sm">{{ Carbon\Carbon::createFromDate($task->created_at)->format('d.m.Y') }}</td>
                            <td>
                                @auth
                                    @can('delete', $task)
                                        <a rel="nofollow" data-confirm="Вы уверены?" data-method="delete"
                                           href="{{ route('tasks.destroy', $task) }}"
                                           class="text-red-600 hover:text-red-900">Удалить</a>
                                    @endcan
                                    <a href="{{ route('tasks.edit', $task) }}"
                                       class="text-blue-600 hover:text-blue-900">Изменить</a>
                                @endauth
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
            </div>
            <div class="mt-4">
                {{ $tasks->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
