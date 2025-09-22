@extends('layouts.home')
@section('content')
<div class="grid max-w-screen-xl px-4 pt-20 pb-8 mx-auto lg:gap-8 xl:gap-0 lg:py-16 lg:grid-cols-12 lg:pt-28">
    <div class="grid col-span-full">
        <h1 class="mb-5">Создать статус</h1>
        {{ html()->modelForm($taskStatus, 'POST', route('task_statuses.store'))->open() }}
        @include('statuses.form')
        <div class="flex flex-col">
            @can('store', App\Models\TaskStatus::class)
            <div class="mt-2">
                {{ html()->submit('Создать')->class(['bg-blue-500', 'hover:bg-blue-700', 'text-white', 'font-bold', 'py-2', 'px-4', 'rounded' => true]) }}
            </div>
            @endcan
        </div>
        {{ html()->closeModelForm() }}
    </div>
</div>
@endsection