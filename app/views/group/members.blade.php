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

<div class="message-holder"><span></span></div>
<div class="row">
    <div class="col-md-3">
        <!-- Left Sidebar -->
        <!-- Left Sidebar -->
        <div class="group-details-holder well">
            <div class="group-details-content">
                <div class="dropdown pull-right">
                    <a data-toggle="dropdown" href="#"><i class="fa fa-gear"></i></a>
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
                <a href="#"><i class="group-code-icon fa fa-lock"></i></a>
                @else
                <a href="#"><i class="group-code-icon fa fa-unlock"></i></a>
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
                <li>
                    <a href="/groups/{{ $groupDetails->group_id }}">
                        <i class="fa fa-chevron-right pull-right"></i>
                        <i class="group-control-icon fa fa-comment"></i> Posts
                    </a>
                </li>
                <li class="active">
                    <a href="/groups/{{ $groupDetails->group_id }}/members">
                        <i class="fa fa-chevron-right pull-right"></i>
                        <i class="group-control-icon fa fa-user"></i> Members
                        <span class="label label-success pull-right">{{ $members->count() }} joined</span>
                    </a>
                </li>
                @if(Auth::user()->account_type == 1)
                <li>
                    <a href="/groups/chat">
                        <i class="group-control-icon fa fa-comments"></i> Start Group Chat
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
                    <a data-toggle="dropdown" href="#" id="group_options">
                        <i class="fa fa-plus-circle"></i>
                    </a>
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

        <div class="member-stream-holder well">
            <div class="stream-title"><h3>Members</h3></div>

            <ul class="member-stream">
                <!-- Group Owner Details -->
                <li class="member-details-holder owner-details-holder">
                    <a href="/profile/{{ $ownerDetails->username }}">
                        @if($ownerDetails->avatar == 'default_avatar.png')
                        <img src="/assets/images/anon.png" width="80" class="pull-left">
                        @else
                        <img src="/assets/avatars/{{ $ownerDetails->hashed_id }}/{{ $ownerDetails->avatar_normal }}"
                        width="80" class="pull-left">
                        @endif
                    </a>
                    <div class="member-content-holder pull-right">
                        <div class="member-name-text">
                            <a href="/profile/{{ $ownerDetails->username }}">
                                {{ $ownerDetails->salutation.' '.$ownerDetails->name }}
                            </a>
                        </div>
                        <div class="member-type text-muted">Teacher (Owner)</div>
                    </div>
                    <div class="clearfix"></div>
                </li>

                @foreach($members as $member)
                @if($member->group_member_id != $ownerDetails->id)
                <li class="member-details-holder">
                    <a href="/profile/{{ $member->username }}">
                        @if($member->avatar == 'default_avatar.png')
                        <img src="/assets/images/{{ $member->avatar }}" width="80" class="pull-left">
                        @else
                        <img src="/assets/avatars/{{ $member->hashed_id }}/{{ $member->avatar_normal }}"
                        width="80" class="pull-left">
                        @endif
                    </a>
                    <div class="member-content-holder pull-right">
                        <div class="dropdown pull-right">
                            <a data-toggle="dropdown" href="#">More <i class="fa fa-chevron-down"></i></a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                <li><a href="#">Change Password</a></li>
                                <li><a href="#">Remove from Group</a></li>
                            </ul>
                        </div>

                        <div class="member-name-text">
                            <a href="/profile/{{ $member->username }}">{{ $member->name }}</a>
                        </div>
                        <div class="member-type text-muted">Student</div>
                        <div class="member-username text-muted">{{ $member->username }}</div>


                    </div>
                    <div class="clearfix"></div>
                </li>
                @endif
                @endforeach
            </ul>
        </div>
    </div>
</div>

@stop

@section('js')
<script src="/assets/js/plugins/groups.js"></script>
@stop
