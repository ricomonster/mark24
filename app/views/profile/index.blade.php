@extends('templates.master')

@section('title')
Profile
@stop

@section('internalCss')
<link href="/assets/css/site/profile.style.css" rel="stylesheet">
<style>
.profile-main-wrapper .well { padding-top: 0; }
.profile-main-wrapper .profile-content-titles { font-size: 14px; font-weight: bold; }

.people-story-wrapper .bar { border-top: 4px solid #76a7fa; margin: 0 -19px; }
.profile-story-wrapper .bar { border-top: 4px solid #e46f61; margin: 0 -19px; }
.profile-education-wrapper .bar { border-top: 4px solid #fbcb43; margin: 0 -19px; }
.profile-places-wrapper .bar { border-top: 4px solid #bc5679; margin: 0 -19px; }

.profile-story-wrapper .tagline-story { margin-bottom: 10px; }
.profile-places-wrapper .current-place,
.profile-places-wrapper .hometown-place { margin-bottom: 10px; }
</style>
<style>

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
                <li class="active">
                    <a href="/profile/{{ $user->username }}">
                        <i class="icon-chevron-right pull-right"></i>
                        Profile Overview
                    </a>
                </li>
                @if($user->account_type == 1)
                <li>
                    <a href="/profile/{{ $user->username }}/students">
                        <i class="icon-chevron-right pull-right"></i>
                        Students
                    </a>
                </li>
                @endif
                @if($user->account_type == 2)
                <li>
                    <a href="/profile/{{ $user->username }}/teachers">
                        <i class="icon-chevron-right pull-right"></i>
                        Teachers
                    </a>
                </li>
                <li>
                    <a href="/profile/{{ $user->username }}/classmates">
                        <i class="icon-chevron-right pull-right"></i>
                        Classmates
                    </a>
                </li>
                <li>
                    <a href="/profile/{{ $user->username }}/activity">
                        <i class="icon-chevron-right pull-right"></i>
                        Activity
                    </a>
                </li>
                @endif
            </ul>
        </div>
    </div>

    <div class="col-md-9 profile-main-wrapper">
        <div class="row">
            <div class="col-md-6">
                <div class="well people-story-wrapper">
                    <div class="bar"></div>
                    <h3>People</h3>
                    <div class="teacher-people">
                        <div class="profile-content-titles">Teachers</div>
                        <div class="content"></div>
                    </div>
                    <div class="student-people">
                        <div class="profile-content-titles">Students</div>
                        <div class="content"></div>
                    </div>
                </div>

                <div class="well profile-education-wrapper">
                    <div class="bar"></div>
                    <h3>Education</h3>
                </div>
            </div>
            <div class="col-md-6">
                @if(!empty($user->tagline) || !empty($user->description))
                <div class="well profile-story-wrapper">
                    <div class="bar"></div>
                    <h3>Story</h3>
                    @if(!empty($user->tagline))
                    <div class="tagline-story">
                        <div class="profile-content-titles">Tagline</div>
                        <div class="content">
                            {{ $user->tagline }}
                        </div>
                    </div>
                    @endif
                    @if(!empty($user->description))
                    <div class="description-story">
                        <div class="profile-content-titles">Description</div>
                        <div class="content">
                            {{ $user->description }}
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                @if(!empty($user->current_place) || !empty($user->hometown) || !empty($user->country))
                <div class="well profile-places-wrapper">
                    <div class="bar"></div>
                    <h3>Places</h3>
                    @if(!empty($user->current_place))
                    <div class="current-place">
                        <div class="profile-content-titles">Currently</div>
                        <div class="content">{{ $user->current_place }}</div>
                    </div>
                    @endif
                    @if(!empty($user->hometown))
                    <div class="hometown-place">
                        <div class="profile-content-titles">Hometown</div>
                        <div class="content">{{ $user->hometown }}</div>
                    </div>
                    @endif
                    @if(!empty($user->country))
                    <div class="country-place">
                        <div class="profile-content-titles">Country</div>
                        <div class="content">{{ $user->country }}</div>
                    </div>
                    @endif
                </div>
                @endif
            </div>
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
