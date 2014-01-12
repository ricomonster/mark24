@foreach($notifications as $notification)
<li role="presentation">
    <a role="menuitem" tabindex="-1" href="{{ $notification->link }}">
        {{ $notification->message }}
    </a>
</li>
@endforeach
