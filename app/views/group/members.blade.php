@extends('templates.master')

@section('title')
{{ $groupDetails->group_name }}
@stop

@section('internalCss')
<link href="/assets/css/site/group.style.css" rel="stylesheet">
<link href="/assets/css/plugins/postcreator.style.css" rel="stylesheet">
<link href="/assets/css/plugins/poststream.style.css" rel="stylesheet">
@stop

@section('content')
<div class="row">
    <div class="col-md-3">
        <!-- Left Sidebar -->
        <div class="group-details-holder well">
            <div class="group-details-content">
                <div class="dropdown pull-right">
                    <a data-toggle="dropdown" href="#">
                        <i class="fa fa-gear"></i>
                    </a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                        @if(Auth::user()->account_type == 1)
                        <li>
                            <a href="#" id="show_settings_modal"
                            data-group-id="{{ $groupDetails->group_id }}">
                                Group Settings
                            </a>
                        </li>
                        @endif
                        @if(Auth::user()->account_type == 2)
                        <li><a href="#" id="leave_group" data-group-id="{{ $groupDetails->group_id }}">Withdraw</a></li>
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
                <a href="#" class="unlock-group pull-left" data-group-id="{{ $groupDetails->group_id }}">
                    <i class="group-code-icon fa fa-lock"></i>
                </a>
                @else
                <a href="#" class="lock-group pull-left" data-group-id="{{ $groupDetails->group_id }}">
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
                <li class="active">
                    <a href="/groups/{{ $groupDetails->group_id }}/members">
                        <i class="fa fa-chevron-right pull-right"></i>
                        <i class="group-control-icon fa fa-user"></i> Members
                        <span class="label label-success pull-right">
                            {{ $stats['member_count'] }} joined
                        </span>
                    </a>
                </li>
                <li>
                    <a href="/groups/{{ $groupDetails->group_id }}/the-forum">
                        <i class="fa fa-chevron-right pull-right"></i>
                        <i class="group-control-icon fa fa-comments-o"></i> Group Forums
                    </a>
                </li>
                <li>
                    <a href="/groups/{{ $groupDetails->group_id }}/the-library">
                        <i class="fa fa-chevron-right pull-right"></i>
                        <i class="group-control-icon fa fa-archive"></i> Group Library
                    </a>
                </li>
                @if(Auth::user()->account_type == 1)
                <li>
                    <a href="/groups/{{ $groupDetails->group_id }}/join-requests">
                        <i class="group-control-icon fa fa-plus"></i> Join Requests
                        <span class="label label-danger pull-right">{{ $stats['join_requests'] }}</span>
                    </a>
                </li>
                <li>
                    @if(empty($ongoingGroupChat))
                    <a href="#" class="show-start-chat"
                    data-group-id="{{ $groupDetails->group_id }}">
                        <i class="group-control-icon fa fa-comments"></i> Start Group Chat
                    </a>
                    @endif
                    @if(!empty($ongoingGroupChat))
                    <a href="/groups/{{ $groupDetails->group_id }}/chat/{{ $ongoingGroupChat->conversation_id }}">
                        <i class="group-control-icon fa fa-comments"></i> Join Group Chat
                        <i class="fa fa-exclamation-circle ongoing-chat pull-right"></i>
                    </a>
                    @endif
                </li>
                <li>
                    <a href="/groups/{{ $groupDetails->group_id }}/chat-archives">
                        <i class="fa fa-chevron-right pull-right"></i>
                        <i class="fa fa-list-alt group-control-icon"></i> Chat Archives
                    </a>
                </li>
                @endif
                @if(Auth::user()->account_type == 2 && !empty($ongoingGroupChat))
                <li>
                    <a href="/groups/{{ $groupDetails->group_id }}/chat/{{ $ongoingGroupChat->conversation_id }}">
                        <i class="group-control-icon fa fa-comments"></i> Join Group Chat
                        <i class="fa fa-exclamation-circle ongoing-chat pull-right"></i>
                    </a>
                </li>
                @endif
            </ul>

            <div class="group-description-holder">{{ $groupDetails->group_description }}</div>
        </div>

        <div class="user-groups-holder">
            <div class="section-title-holder">
                <span>Other Groups</span>
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
        <div class="member-stream-holder well">
            <div class="stream-title"><h3>Members</h3></div>

            <ul class="member-stream">
                <!-- Group Owner Details -->
                <li class="member-details-holder owner-details-holder">
                    <a href="/profile/{{ $ownerDetails->username }}">
                        {{ Helper::avatar(80, "normal", "pull-left", $ownerDetails->id) }}
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
                        {{ Helper::avatar(80, "normal", "pull-left", $member->id) }}
                    </a>
                    <div class="member-content-holder pull-right">
                        @if(Auth::user()->account_type == 1 && $member->account_type == 2)
                        <div class="dropdown pull-right">
                            <a data-toggle="dropdown" href="#">More <i class="fa fa-chevron-down"></i></a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                                <li><a href="#" class="show-change-password"
                                data-user-id="{{ $member->group_member_id }}">Change Password</a></li>
                                <li><a href="#">Remove from Group</a></li>
                            </ul>
                        </div>
                        @endif

                        <div class="member-name-text">
                            <a href="/profile/{{ $member->username }}">{{ $member->name }}</a>
                        </div>
                        <div class="member-type text-muted">
                            @if($member->account_type == 1)
                            Teacher
                            @endif
                            @if($member->account_type == 2)
                            Student
                            @endif
                        </div>
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
<script src="/assets/js/sitefunc/groups.js"></script>
@if(Auth::user()->account_type == 1)
<script>
(function($) {
    // gets the change password
    $(document).on('click', '.show-change-password', function(e) {
        $.get(
            '/ajax/modal/show-change-password',
            { user_id : $(this).data('user-id') },
            function(response) {
                $('#the_modal').modal('show')
                $('#the_modal').html(response);
        });

        e.preventDefault();
    });

    $(document).on('submit', '.change-password-modal', function(e) {
        e.preventDefault();
    })

    $(document).on('click', '#trigger_reset_password', function(e) {
        $('.message-holder').show().find('span').text('Saving...');

        if($('#reset-password').val() == '' || $('#reset-password').val().length < 6) {
            $('#reset-password').parent().addClass('has-error');
            $('#reset-password').siblings('.alert').addClass('alert-danger')
                .html('Must be 6+ chars').show();

            $('.message-holder').hide();
        } else {
            $('#reset-password').parent().removeClass('has-error');
            $('#reset-password').siblings('.alert').hide();

            $.ajax({
                type : 'post',
                url : '/ajax/modal/reset-password',
                data : $('.change-password-modal').serialize(),
                dataType : 'json',
                async : false
            }).done(function(response) {
                if(!response.error) {
                    $('#the_modal').modal('hide');

                    $('.message-holder').show().find('span').text('Password has been reset...');

                    setInterval(function() {
                        $('.message-holder').hide();
                    }, 3000)
                }
            })
        }

        e.preventDefault();
    })
})(jQuery);
</script>
@endif
@stop
