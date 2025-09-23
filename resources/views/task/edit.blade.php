<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Изменение задачи') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <form method="POST" action="{{ route('tasks.update', $task) }}" class="mt-6 space-y-6">
                    @csrf
                    @method('PATCH')

                    <div>
                        <x-input-label for="name" :value="__('Имя')"/>
                        <x-text-input id="name" name="name" type="text" value="{{ $task->name }}"
                                      class="mt-1 block w-full" required autofocus/>
                        <x-input-error class="mt-2" :messages="$errors->get('name')"/>
                    </div>

                    <div>
                        <x-input-label for="description" :value="__('Описание')"/>
                        <x-textarea-input id="description" name="description" :value="$task->description"
                                          cols="30"
                                          rows="6"
                                          class="mt-1 block w-full"></x-textarea-input>
                        <x-input-error class="mt-2" :messages="$errors->get('description')"/>
                    </div>

                    <div>
                        <x-input-label for="status_id" :value="__('Статус')"/>
                        <select id="status_id" name="status_id"
                                class="mt-1 block border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            @foreach($statuses as $status)
                                @if($status->id === $task->status->id)
                                    <option selected="selected" value="{{ $status->id }}">{{ $status->name }}</option>
                                @else
                                    <option value="{{ $status->id }}">{{ $status->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('status_id')"/>
                    </div>

                    <div>
                        <x-input-label for="assigned_to_id" :value="__('Исполнитель')"/>
                        <select id="assigned_to_id" name="assigned_to_id"
                                class="mt-1 block border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value=""></option>
                            @foreach($users as $user)
                                @if($user->id === optional($task->assignedTo)->id)
                                    <option selected="selected" value="{{ $user->id }}">{{ $user->name }}</option>
                                @else
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('assigned_to_id')"/>
                    </div>

                    <div>
                        <x-input-label for="labels[]" :value="__('Метки')"/>
                        <select id="labels[]" name="labels[]" multiple=""
                                class="mt-1 block border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            @foreach($labels as $label)
                                @if($task->labels->where('id', '=', $label->id)->isNotEmpty())
                                    <option selected="selected" value="{{ $label->id }}">{{ $label->name }}</option>
                                @else
                                    <option value="{{ $label->id }}">{{ $label->name }}</option>
                                @endif
                            @endforeach
                        </select>
                        <x-input-error class="mt-2" :messages="$errors->get('labels')"/>
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Обновить') }}</x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
