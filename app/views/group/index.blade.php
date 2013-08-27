@extends('templates.master')

@section('title')
Group
@stop

@section('internalCss')
<link href="/assets/css/site/group.style.css" rel="stylesheet">
<link href="/assets/css/plugins/postcreator.style.css" rel="stylesheet">
<link href="/assets/css/plugins/poststream.style.css" rel="stylesheet">
<style type="text/css">

</style>
@stop

@section('content')

<div class="row">
    <div class="col-md-3">
        <!-- Left Sidebar -->
        <div class="group-details-holder well">
            <div class="group-details-content">
                <div class="dropdown pull-right">
                    <a data-toggle="dropdown" href="#"><i class="icon-gear"></i></a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                        <li><a href="#"></a></li>
                    </ul>
                </div>
                <div class="group-name">{{ $groupDetails->group_name }}</div>
                <div class="text-muted">Group</div>
            </div>

            <div class="group-code-holder">
                <div class="group-code-title">Group Code</div>
                @if($groupDetails->group_code == 'LOCKED')
                <a href="#"><i class="group-code-icon icon-lock"></i></a>
                @else
                <a href="#"><i class="group-code-icon icon-unlock"></i></a>
                @endif
                <select class="form-control">
                    @if($groupDetails->group_code == 'LOCKED')
                    <option value="">LOCKED</option>
                    @else
                    <option value="">{{ $groupDetails->group_code }}</option>
                    @endif
                    <option value="">Reset</option>
                </select>
            </div>

            <ul class="nav nav-pills nav-stacked group-controls">
                <li class="active">
                    <a href="/groups/{{ $groupDetails->group_id }}">
                        <i class="icon-chevron-right pull-right"></i>
                        <i class="group-control-icon icon-comment-alt"></i> Posts
                    </a>
                </li>
                <li>
                    <a href="/groups/{{ $groupDetails->group_id }}/members">
                        <i class="icon-chevron-right pull-right"></i>
                        <i class="group-control-icon icon-user"></i> Members
                        <span class="label label-success pull-right">100 joined</span>
                    </a>
                </li>
            </ul>
        </div>

        <div class="user-groups-holder">
            <div class="section-title-holder">
                <span>Groups</span>
                <div class="dropdown pull-right">
                    <a data-toggle="dropdown" href="#" id="group_options"><i class="icon-plus-sign"></i></a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                        @if(Auth::user()->account_type == 1)
                        <li><a href="#" id="show_create_group">Create</a></li>
                        @endif
                        <li><a href="#" id="show_join_group">Join</a></li>
                    </ul>
                </div>
            </div>
            <ul class="nav nav-pills nav-stacked">
                @foreach($groups as $group)
                <li class="<?php echo ($group->group_id == $groupDetails->group_id) ? 'active' : null; ?>">
                    <a href="/groups/{{ $group->group_id }}">{{ $group->group_name }}</a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="col-md-9">
        <!-- Main Content -->
        <div class="post-creator-holder">
            <ul class="nav nav-tabs" id="post_creator_options">
                <li class="active"><a href="#note"><i class="icon-edit"></i> Note</a></li>
                <li><a href="#alert"><i class="icon-exclamation-sign"></i> Alert</a></li>
                <li><a href="#assignment"><i class="icon-check"></i> Assigment</a></li>
                <li><a href="#quiz"><i class="icon-question-sign"></i> Quiz</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane well active" id="note">
                    {{ Form::open(array('url' => 'ajax/post_creator/create_note')) }}
                        <textarea name="note" id="note_holder" class="form-control"
                        placeholder="Type your note here..."></textarea>
                    {{ Form::close() }}
                </div>
                <div class="tab-pane well" id="alert">
                    Alert
                </div>
                <div class="tab-pane well" id="assignment">
                    Assignment
                </div>
                <div class="tab-pane well" id="quiz">
                    Quiz
                </div>
            </div>
        </div>

        <div class="post-stream-holder well">
            <div class="stream-title"><h4>Latest Posts</h4></div>

            <ul class="post-stream">
                @for($x = 0; $x < 5; $x++)
                <li class="post-holder">
                    <a href="#"><img src="/assets/images/anon.png" width="50" class="img-rounded pull-left"></a>
                    <div class="post-content pull-left">
                        <div class="post-content-header">
                            <a href="#" class="post-sender-name">Juan dela Cruz</a>
                            <span class="subtext sender-to-receiver">to</span>
                            <a href="#" class="post-receiver-name">Group 1</a>
                        </div>
                        <div class="post-content-container">
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                            Vivamus arcu lectus, euismod et lectus non, euismod aliquet orci.
                            Nam auctor eget ligula non porta.
                            Integer feugiat nunc sed laoreet euismod.
                        </div>
                    </div>
                    <div class="post-etcs pull-left">
                        <ul class="post-etcs-holder">
                            <li><a href="#"><i class="icon-thumbs-up-alt"></i> Like it</a></li>
                            <li><a href="#"><i class="icon-comment-alt"></i> Reply</a></li>
                            <li><a href="#"><i class="icon-time"></i> August 25, 2013</a></li>
                        </ul>
                    </div>
                    <div class="clearfix"></div>
                </li>
                @endfor
            </ul>
        </div>
    </div>
</div>

@stop

@section('js')
<script>
(function($) {
    $('#post_creator_options a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
    });
})(jQuery)
</script>
@stop
