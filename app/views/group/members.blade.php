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
                <li>
                    <a href="/groups/{{ $groupDetails->group_id }}">
                        <i class="icon-chevron-right pull-right"></i>
                        <i class="group-control-icon icon-comment-alt"></i> Posts
                    </a>
                </li>
                <li class="active">
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
        <div class="member-stream-holder well">
            <div class="stream-title"><h3>Members</h3></div>

            <ul class="member-stream">
                <!-- Group Owner Details -->
                <li class="member-details-holder owner-details-holder">
                    <a href="#"><img src="/assets/images/anon.png" width="80" class="pull-left"></a>
                    <div class="member-content-holder pull-right">
                        <div class="member-name-text">
                            <a href="#">{{ $ownerDetails->salutation.' '.$ownerDetails->name }}</a>
                        </div>
                        <div class="member-type text-muted">Teacher (Owner)</div>
                    </div>
                    <div class="clearfix"></div>
                </li>

                @foreach($members as $member)
                @if($member->group_member_id != $ownerDetails->id)
                <li class="member-details-holder">
                    <a href="#"><img src="/assets/images/default_avatar.png" width="80" class="pull-left"></a>
                    <div class="member-content-holder pull-right">
                        <div class="dropdown pull-right">
                            <a data-toggle="dropdown" href="#">More <i class="icon-chevron-down"></i></a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                <li><a href="#">Change Password</a></li>
                                <li><a href="#">Remove from Group</a></li>
                            </ul>
                        </div>

                        <div class="member-name-text">
                            <a href="#">{{ $member->name }}</a>
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
<script>
(function($) {
    $('#post_creator_options a').click(function (e) {
        e.preventDefault()
        $(this).tab('show')
    });
})(jQuery)
</script>
@stop
