@foreach (session('flash_notification', collect())->toArray() as $message)
    @if ($message['overlay'])
        @include('flash::modal', [
            'modalClass' => 'flash-modal',
            'title'      => $message['title'],
            'body'       => $message['message']
        ])
    @else
        @switch($message['level'])
            @case('success')
                <x-success-flash :message="$message"></x-success-flash>
                @break

            @case('danger')
                <x-danger-flash :message="$message"></x-danger-flash>
                @break

            @case('warning')
                <x-warning-flash :message="$message"></x-warning-flash>
                @break
        @endswitch
    @endif
@endforeach

{{ session()->forget('flash_notification') }}
