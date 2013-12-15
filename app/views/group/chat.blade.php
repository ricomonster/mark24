@extends('templates.master')

@section('title')
{{ $groupDetails->group_name }} - Chat
@stop

@section('internalCss')
<link href="/assets/css/plugins/postcreator.style.css" rel="stylesheet">
<link href="/assets/css/plugins/poststream.style.css" rel="stylesheet">
<link href="/assets/css/plugins/chosen.css" rel="stylesheet">
<style type="text/css">
.group-chat-wrapper .well { padding: 0; }
.group-chat-wrapper .group-chat-details { border-bottom: 1px solid #dfe4e8; padding: 10px 20px; }
.group-chat-proper .group-chat-stream-holder .chat-stream {
    height: 400px;
    list-style: none;
    margin: 0;
    overflow: scroll;
    overflow-x: hidden;
    padding: 10px 20px 0;
}

.group-chat-proper .group-chat-stream-holder .chat-stream .chat-content {
    margin-bottom: 10px;
}

.chat-stream .chat-content .chat-details { margin-left: 60px; }
.chat-stream .chat-content .chat-details .chat-user-details a { font-weight: bold; }

.group-chat-proper .group-chat-messenger { margin-top: 10px; padding: 5px 10px 10px; }
</style>
@stop

@section('content')

<div class="message-holder"><span></span></div>
<div class="row group-chat-wrapper" data-group-id="{{ $groupDetails->group_id }}">
    <div class="col-md-3">
        <div class="well">
            <ul class="nav nav-pills nav-stacked student-lists">
                @foreach($members as $member)
                @if(Auth::user()->id != $member->id)
                <li><a href="#">{{ $member->name }}</a></li>
                @endif
                @endforeach
            </ul>
        </div>
    </div>

    <div class="col-md-6">
        <div class="well">
            <div class="group-chat-details">
                <h3>{{ $groupDetails->group_name }}</h3>
            </div>
            <div class="group-chat-proper">
                <div class="group-chat-stream-holder">
                    <ul class="chat-stream"></ul>
                </div>

                {{ Form::open(array('url'=>'ajax/groups/send-message', 'class'=>'group-chat-messenger')) }}
                    <textarea name="message" class="form-control message-box"></textarea>
                {{ Form::close() }}
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="well"></div>
    </div>
</div>

@stop

@section('js')
<script src="/assets/js/plugins/bootstrap-datepicker.js"></script>
<script src="/assets/js/plugins/groups.js"></script>
<script src="/assets/js/sitefunc/chat.js"></script>
@stop


