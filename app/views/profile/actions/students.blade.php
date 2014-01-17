@extends('templates.master')

@section('title')
{{ $user->salutation.' '.$user->name }} - Students
@stop

@section('internalCss')
<link href="/assets/css/site/profile.style.css" rel="stylesheet">
<style>
    .profile-proper-container .student-list-stream {
        list-style: none;
        margin: 0;
        padding: 0 20px 20px;
    }

    .student-list-stream li {
        display: inline-block;
        margin: 15px 11px 0 0;
        vertical-align: top;
        width: 150px;
        word-wrap: break-word;
        *display: inline;
        *zoom: 1;
    }
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
        <div class="etc-details">
            <span class="profile-user-type">
            @if($user->account_type == 1) Teacher @endif
            @if($user->account_type == 2) Student @endif
            </span>
            @if(!empty($user->country))
            <span class="divider text-muted">&bull;</span>
            <span class="profile-user-country">{{ $user->country }}</span>
            @endif
        </div>
        <hr />
        @include('profile.headerstats')
    </div>
    <div class="clearfix"></div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="profile-control-holder well">
            <ul class="nav nav-pills nav-stacked profile-controls">
                <li>
                    <a href="/profile/{{ $user->username }}">
                        <i class="icon-chevron-right pull-right"></i>
                        Profile Overview
                    </a>
                </li>
                <li class="active">
                    <a href="/profile/{{ $user->username }}/students">
                        <i class="icon-chevron-right pull-right"></i>
                        Students
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="col-md-9">
        <div class="profile-proper-container well">
            <div class="page-header">
                <h3>Students</h3>
            </div>
            @if($students->isEmpty())
                <p>No students</p>
            @endif
            @if(!$students->isEmpty())
            <ul class="student-list-stream">
                @foreach($students as $student)
                <li>
                    <a href="/profile/{{ $student->username }}">
                        {{ Helper::avatar(150, "large", "img-circle", $student->id) }}
                    </a>
                </li>
                @endforeach
            </ul>
            @endif
        </div>
    </div>
</div>

@stop

@section('js')
<script>
(function($) {

})(jQuery)
</script>
@stop
