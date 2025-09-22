<div>
    <label for="name">Имя</label>
</div>
<div class="mt-2">
    <input class="rounded border-gray-300 w-1/3" type="text" name="name" id="name" value="{{$label->name}}">
</div>
@if ($errors->has('name'))
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->get('name') as $error)
        <li class="text-rose-600">{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<div class="mt-2">
    <label for="description">Описание</label>
</div>
<div class="mt-2">
    <textarea class="rounded border-gray-300 w-1/3 h-32" name="description" id="description">{{$label->description}}</textarea>
</div>