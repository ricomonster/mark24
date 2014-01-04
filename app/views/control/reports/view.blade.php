@extends('templates.admin')

@section('title')
Dashboard - Report #{{ $report->report_id }}
@stop

@section('internalCss')
<style>
.panel .panel-heading { font-size: 18px; font-weight: bold; }
</style>
@stop

@section('content')
<section class="container">
    <section class="row">
        <section class="col-md-3">
            <ul class="nav nav-stacked nav-pills">
                <li><a href="?sort=stats">Stats</a></li>
                <li><a href="?sort=users">Users</a></li>
                <li><a href="?sort=groups">Groups</a></li>
                <li><a href="?sort=posts">Posts</a></li>
                <li><a href="?sort=the-forum">The Forum</a></li>
                <li><a href="?sort=the-library">The Library</a></li>
                <li class="active"><a href="?sort=reports">Reports</a></li>
            </ul>
        </section>
        <section class="col-md-9">
            <div class="panel panel-default">
                <!-- Default panel contents -->
                <div class="panel-heading">
                    Report #{{ $report->report_id }}
                </div>
                <table class="table">
                    <tbody>
                        <tr>
                            <th width="150">Reported</th>
                            <td>{{ Helper::timestamp($report->report_timestamp) }}</td>
                        </tr>
                        <tr>
                            <th>Reported By</th>
                            <td>
                                {{ $report->name }}
                                @if($report->account_type == 1)
                                <span class="text-muted">(Teacher)</span>
                                @endif
                                @if($report->account_type == 2)
                                <span class="text-muted">(Student)</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Details</th>
                            <td>{{{ $report->details }}}</td>
                        </tr>
                        <tr>
                            <th>User Location</th>
                            <td>{{ $report->location }}</td>
                        </tr>
                        <tr>
                            <th>Operating System</th>
                            <td>{{ $report->os }}</td>
                        </tr>
                        <tr>
                            <th>Browser</th>
                            <td>{{ $report->browser }}</td>
                        </tr>
                        <tr>
                            <th>IP Address</th>
                            <td>{{ $report->ip }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </section>
</section>
@stop

@section('js')

@stop
