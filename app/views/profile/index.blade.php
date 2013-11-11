@extends('templates.master')

@section('title')
Profile
@stop

@section('internalCss')
<style>
    .profile-header .profile-user-details { margin-left: 20px; width: 82%; }
    .profile-header .profile-user-details .profile-user-type { color: #839096; font-size: 13px; }
    .profile-header .profile-user-details hr { margin: 10px 0; }
    .profile-header .profile-user-details .user-stats { list-style: none; margin: 0; padding: 0; }
    .profile-header .profile-user-details .user-stats li { display: inline-block; margin-right: 50px; text-align: center; }
    .profile-header .profile-user-details .user-stats li p { margin: 0; }
    .profile-header .profile-user-details .user-stats li .stat-number { color: #3784d5; margin: 0; padding: 0; }
    .profile-header .profile-user-details .user-stats li .stat-name { color: #839096; font-size: 13px; }

    .profile-control-holder { padding: 0; }
    .profile-control-holder ul li { margin: 0; }
    .profile-control-holder ul li a {
        background-color: #ffffff;
        border: 1px solid #dfe4e8 !important;
        border-top: 0 !important;
        border-radius: 0;
    }

    .profile-control-holder ul li.active a { background-color: #f3f5f7; color: #2a6496; }
    .profile-control-holder ul li.active i {  }
</style>
@stop

@section('content')

<div class="profile-header well">
    <div class="profile-avatar pull-left">
        {{ Helper::avatar(140, "large", "img-rounded", $user->id) }}
    </div>
    <div class="profile-user-details pull-left">
        <h3 class="profile-user-fullname">
            @if(isset($user->salutation))
            {{ $user->salutation }}
            @endif
            {{ $user->name }}
        </h3>
        <div class="profile-user-type">
            @if($user->account_type == 1) Teacher @endif
            @if($user->account_type == 2) Student @endif
        </div>
        <hr />
        <ul class="user-stats">
            <li>
                <h3 class="stat-number">200</h3>
                <p class="stat-name">Students</p>
            </li>
            <li>
                <h3 class="stat-number">100</h3>
                <p class="stat-name">Connections</p>
            </li>
            <li>
                <h3 class="stat-number">100</h3>
                <p class="stat-name">Library Items</p>
            </li>
        </ul>
    </div>
    <div class="clearfix"></div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="profile-control-holder well">
            <ul class="nav nav-pills nav-stacked profile-controls">
                <li class="active">
                    <a href="#">
                        <i class="icon-chevron-right pull-right"></i>
                        Profile Overview
                    </a>
                </li>
                <li>
                    <a href="#">
                        <i class="icon-chevron-right pull-right"></i>
                        Communities
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="col-md-9">
        <div class="well"></div>
    </div>
</div>

@stop

@section('js')
<script>
(function($) {

})(jQuery)
</script>
@stop
