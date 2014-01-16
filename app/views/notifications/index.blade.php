@extends('templates.master')

@section('title')
Notifications
@stop

@section('internalCss')
<style type="text/css">
.notifications-wrapper {}
.notifications-wrapper .notifications-holder { padding: 0; }
.notifications-holder .page-header { margin: 0; padding: 10px 20px; }
.notifications-holder .page-header h3 { margin: 0; padding: 0; }
.notifications-holder .notifications-stream { list-style: none; margin: 0; padding: 10px 0; }
.notifications-holder .notifications-stream li.date { margin-top: 10px; padding: 5px 20px; }
.notifications-holder .notifications-stream li.date:first-child { margin-top: 0; }
.notifications-holder .notifications-stream li.date span { font-weight: bold; }
.notifications-holder .notifications-stream li a { padding: 5px 20px; }
.notifications-holder .notifications-stream li a i { margin: 0 5px 0; }
.notifications-holder .notifications-stream li a:hover i { text-decoration: none; }
</style>
@stop

@section('content')
<div class="row notifications-wrapper">
    <div class="col-md-9">
        <div class="well notifications-holder">
            <div class="page-header"><h3>Notifications</h3></div>
            <ul class="notifications-stream">
                @foreach($notifications as $date)
                <li class="date"><span>{{ Helper::notificationDates($date->date) }}</span></li>
                @foreach($date->notifications as $notification)
                <li>
                    <a href="{{ $notification->link }}">
                        <i class="fa {{ $notification->icon }}"></i>
                        <span class="message">{{ $notification->message }}</span>
                    </a>
                </li>
                @endforeach
                @endforeach
            </ul>
        </div>
    </div>
</div>
@stop

@section('js')
<script>

</script>
@stop
