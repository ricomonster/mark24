@extends('templates.master')

@section('title')
Group
@stop

@section('internalCss')
<link href="/assets/css/site/group.style.css" rel="stylesheet">
<link href="/assets/css/plugins/postcreator.style.css" rel="stylesheet">
<link href="/assets/css/plugins/poststream.style.css" rel="stylesheet">
<link href="/assets/css/plugins/chosen.css" rel="stylesheet">
<style type="text/css">

</style>
@stop

@section('content')

<div class="message-holder"><span></span></div>
<div class="row">
    <div class="col-md-3">
        <!-- Left Sidebar -->
        <div class="group-details-holder well">
            <div class="group-details-content">
                <div class="dropdown pull-right">
                    <a data-toggle="dropdown" href="#"><i class="icon-gear"></i></a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                        @if(Auth::user()->account_type == 1)
                        <li><a href="#" class="show-group-settings">Group Settings</a></li>
                        @endif
                        @if(Auth::user()->account_type == 2)
                        <li><a href="#" class="leave-group">Leave Group</a></li>
                        @endif
                    </ul>
                </div>
                <div class="group-name">{{ $groupDetails->group_name }}</div>
                <div class="text-muted">Group</div>
            </div>

            @if(Auth::user()->account_type == 1)
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
            @endif

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
                        <span class="label label-success pull-right">{{ $memberCount }} joined</span>
                    </a>
                </li>
                @if(Auth::user()->account_type == 1)
                <li>
                    <a href="/groups/chat">
                        <i class="group-control-icon icon-comments-alt"></i> Start Group Chat
                    </a>
                </li>
                @endif
            </ul>

            <div class="group-description-holder">{{ $groupDetails->group_description }}</div>
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
        <div class="modal fade" id="the_modal" tabindex="-1" role="dialog"
        aria-labelledby="the_modal_label" aria-hidden="true"></div>

        @include('plugins.postcreator')

        @include('plugins.poststream')
    </div>
</div>

@stop

@section('js')
<script src="/assets/js/plugins/chosen.js"></script>
<script src="/assets/js/plugins/expanding.js"></script>
<script src="/assets/js/plugins/postcreator.js"></script>
<script src="/assets/js/plugins/groups.js"></script>
<script src="/assets/js/sitefunc/comment.creator.js"></script>
@stop
