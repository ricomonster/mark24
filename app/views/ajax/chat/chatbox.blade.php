<div class="chat-boxes-wrapper">
    @foreach($chats as $key => $chat)
        <div class="chat-box" data-conversation-id="{{ $chat->conversation_id }}">
            <div class="chat-box-header">{{ $chat->group_name }}</div>
            <div class="chat-box-content">
                <ul class="chat-stream">
                    @foreach($chat->messages as $chat)
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
                            {{ Helper::avatar(38, "small", "img-rounded avatar", $chat->id) }}
                            <div class="chat-message">{{{ $chat->message }}}</div>
                            <div class="clearfix"></div>
                        </div>
                        <div class="clearfix"></div>
                    </li>
                    @endforeach
                </ul>
                {{ Form::open(array('url' => '')) }}
                    <textarea class="form-control chat-text"
                    placeholder="Write a message..."
                    data-conversation-id="{{ $chat->conversation_id }}"></textarea>
                {{ Form::close() }}
            </div>
        </div>
    @endforeach
</div>