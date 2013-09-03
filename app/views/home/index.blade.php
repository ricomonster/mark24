@extends('templates.master')

@section('title')
Home
@stop

@section('internalCss')
<link href="/assets/css/site/home.style.css" rel="stylesheet">
<link href="/assets/css/plugins/postcreator.style.css" rel="stylesheet">
<link href="/assets/css/plugins/poststream.style.css" rel="stylesheet">
<link href="/assets/css/plugins/chosen.css" rel="stylesheet">
@stop

@section('content')

<div class="row">
    <div class="col-md-3">
        <!-- Left Sidebar -->
        <div class="user-details-holder well">
            <img src="/assets/images/anon.png" width="50" class="img-rounded pull-left">
            <div class="user-details-content pull-left">
                @if(Auth::user()->account_type == 1)
                <a href="#">Hi, {{ Auth::user()->salutation.' '.Auth::user()->lastname }}</a>
                <div class="user-type">Teacher</div>
                @else
                <a href="#">Hi, {{ Auth::user()->name }}</a>
                <div class="user-type">Student</div>
                @endif
            </div>
            <div class="clearfix"></div>
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
                @if(!empty($groups))
                @foreach($groups as $group)
                <li><a href="/groups/{{ $group->group_id }}">{{ $group->group_name }}</a></li>
                @endforeach
                @else
                <li>No Groups Found</li>
                @endif
            </ul>
        </div>

    </div>
    <div class="col-md-9">

        <div class="modal fade" id="the_modal" tabindex="-1" role="dialog"
        aria-labelledby="the_modal_label" aria-hidden="true"></div>

        <!-- Main Content -->
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
@stop
