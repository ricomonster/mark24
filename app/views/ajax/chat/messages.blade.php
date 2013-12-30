@if(!$chats->isEmpty())
@foreach($chats as $chat)
<li class="chat-content" data-chat-id="{{ $chat->chat_conversation_id }}">
    {{ Helper::avatar(50, "small", "img-rounded pull-left", $chat->id) }}
    <div class="chat-details">
        <div class="chat-user-details">
            <span class="pull-right text-muted">
                {{ Helper::chatTimestamp($chat->chat_timestamp) }}
            </span>
            @if($chat->id == Auth::user()->id)
            <a href="#">Me</a>
            @endif
            @if($chat->id != Auth::user()->id)
            <a href="#">{{ $chat->name }}</a>
            @endif
        </div>
        <div class="chat-message">{{{ $chat->message }}}</div>
    </div>
    <div class="clearfix"></div>
</li>
@endforeach
@endif
