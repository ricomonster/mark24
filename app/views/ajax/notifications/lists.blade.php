@foreach($notifications as $notification)
<li role="presentation">
    <a role="menuitem" tabindex="-1" href="{{ $notification->link }}">
        <i class="fa {{ $notification->icon }}"></i> {{ $notification->message }}
    </a>
</li>
@endforeach
