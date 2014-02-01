@extends('templates.master')

@section('title')
{{ $groupDetails->group_name }} - Chat
@stop

@section('internalCss')
<link href="/assets/css/site/group.style.css" rel="stylesheet">
<style type="text/css">
.group-chat-wrapper .well { padding: 0; }
.group-chat-wrapper .group-details-holder .group-controls .dropdown .fa-gear { font-size: 18px; }
.group-chat-wrapper .group-details-holder .group-controls .dropdown .dropdown-menu li a {
    background-color: #ffffff;
    border: 0 !important;
}

.group-chat-wrapper .group-details-holder .group-controls .dropdown .dropdown-menu li a:hover {
    background-color: #428bca;
    color: #ffffff;
    text-decoration: none;
}

.group-chat-wrapper .group-chat-details { border-bottom: 1px solid #dfe4e8; padding: 10px 20px; }
.group-chat-proper .group-chat-stream-holder .chat-stream {
    height: 300px;
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
.group-chat-wrapper .student-lists .user-online { color: #65a830; }
.group-chat-wrapper .section-title-holder { background-color: #757f93; color: #ffffff; padding: 12px; }
</style>
@stop

@section('content')
<div class="row group-chat-wrapper" data-group-id="{{ $groupDetails->group_id }}"
data-conversation-id="{{ $conversation->conversation_id }}">
    <div class="col-md-3">
        <div class="group-details-holder well">
            <div class="group-details-content">
                <div class="group-name">{{ $groupDetails->group_name }}</div>
                <div class="text-muted">Group</div>
            </div>

            @if(Auth::user()->account_type == 1)
            <div class="group-code-holder">
                <div class="group-code-title">Group Code</div>
                @if($groupDetails->group_code == 'LOCKED')
                <a href="#" class="pull-left unlock-group" data-group-id="{{ $groupDetails->group_id }}">
                    <i class="group-code-icon fa fa-lock"></i>
                </a>
                @else
                <a href="#" class="pull-left lock-group" data-group-id="{{ $groupDetails->group_id }}">
                    <i class="group-code-icon fa fa-unlock"></i>
                </a>
                @endif
                <div class="group-code-control input-group pull-left">
                    <input type="text" class="group-code form-control" readonly
                    value="<?php echo ($groupDetails->group_code == 'LOCKED') ? 'LOCKED' : $groupDetails->group_code; ?>">
                    <div class="input-group-btn">
                        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
                        <ul class="dropdown-menu pull-right">
                            <li>
                                <a href="#" class="reset-group-code"
                                data-group-id="{{ $groupDetails->group_id }}">Reset</a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            @endif

            <ul class="nav nav-pills nav-stacked group-controls">
                <li>
                    <a href="/groups/{{ $groupDetails->group_id }}">
                        <i class="fa fa-chevron-right pull-right"></i>
                        <i class="group-control-icon fa fa-comment"></i> Posts
                    </a>
                </li>
                <li>
                    <a href="/groups/{{ $groupDetails->group_id }}/members">
                        <i class="fa fa-chevron-right pull-right"></i>
                        <i class="group-control-icon fa fa-user"></i> Members
                        <span class="label label-success pull-right">{{ $members->count() }} joined</span>
                    </a>
                </li>
                <li>
                    <a href="/groups/{{ $groupDetails->group_id }}/forums">
                        <i class="fa fa-chevron-right pull-right"></i>
                        <i class="group-control-icon fa fa-comments-o"></i> Group Forums
                    </a>
                </li>
                @if(Auth::user()->account_type == 1)
                <li class="active dropdown">
                    <a data-toggle="dropdown" href="#">
                        <i class="group-control-icon fa fa-comments"></i> Group Chat
                        <i class="fa fa-gear pull-right"></i>
                    </a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                        <li>
                            <a href="#" class="stop-group-chat">
                                Stop Group Chat
                            </a>
                        </li>
                    </ul>
                </li>
                @endif
                @if(Auth::user()->account_type == 2)
                <li class="active">
                    <a href="#">
                        <i class="group-control-icon fa fa-comments"></i> Group Chat
                    </a>
                </li>
                @endif
            </ul>

            <div class="group-description-holder">{{ $groupDetails->group_description }}</div>
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
        <div class="well">
            <div class="section-title-holder">
                <span>Group members</span>
            </div>
            <ul class="nav nav-pills nav-stacked student-lists">
                @foreach($members as $member)
                @if(Auth::user()->id != $member->id)
                <li><a href="#">
                    {{ $member->name }}
                    {{ Helper::checkUserOnline($member->online_timestamp) }}
                </a></li>
                @endif
                @endforeach
            </ul>
        </div>
    </div>
</div>

@stop

@section('js')
<script src="/assets/js/plugins/bootstrap-datepicker.js"></script>
<script src="/assets/js/sitefunc/groups.js"></script>
<script src="/assets/js/sitefunc/chat.js"></script>
@stop


