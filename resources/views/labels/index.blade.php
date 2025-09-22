@extends('layouts.home')
@section('content')
@vite(['resources/css/app.css', 'resources/js/app.js'])
<section class="bg-white dark:bg-gray-900">
    <div class="grid max-w-screen-xl px-4 pt-20 pb-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12 lg:pt-28">
        @include('flash::message')
        <div class="grid col-span-full">
            <h1 class="mb-5">Метки</h1>
            @can('store', App\Models\Label::class)
            <div>
                <a href="{{route('labels.create')}}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Создать метку </a>
            </div>
            @endcan

            <table class="mt-4">
                <thead class="border-b-2 border-solid border-black text-left">
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Описание</th>
                        <th>Дата создания</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                @foreach ($labels as $label)
                <tr class="border-b border-dashed text-left">
                    <td>{{$label->id}}</td>
                    <td>{{$label->name}}</td>
                    <td>{{$label->description}}</td>
                    <td>{{$label->getFormattedUpdateTime()}}</td>
                    <td>
                        @can('update', App\Models\Label::class)
                        <a
                            class="text-blue-600 hover:text-blue-900"
                            href="{{route('labels.edit', $label->id)}}">
                            Изменить

                        </a>
                        @endcan
                        @can('delete', App\Models\Label::class)
                        <a
                            data-confirm="Вы уверены?"
                            data-method="delete"
                            class="text-red-600 hover:text-red-900"
                            rel="nofollow"
                            href="{{route('labels.destroy', $label->id)}}">
                            Удалить
                        </a>
                        @endcan

                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</section>
@endsection