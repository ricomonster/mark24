@if(!$chats->isEmpty())
@foreach($chats as $chat)
<li class="chat-holder {{ ($chat->id == Auth::user()->id) ? 'self' : 'others' }}"
data-chat-id="{{ $chat->chat_conversation_id }}">
    <div class="timestamp text-muted">
        <a href="/profile/{{ $chat->username }}">
            {{ ($chat->id == Auth::user()->id) ? 'Me' : $chat->salutation.$chat->name }}
        </a>
        &bull;
        {{ Helper::chatTimestamp($chat->chat_timestamp) }}
    </div>
    <div class="chat-content">
        {{ Helper::avatar(50, "small", "img-rounded avatar", $chat->id) }}
        <div class="chat-message">{{{ $chat->message }}}</div>
        <div class="clearfix"></div>
    </div>
    <div class="clearfix"></div>
</li>
@endforeach
@endif
