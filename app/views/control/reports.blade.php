@extends('templates.admin')

@section('title')
Dashboard - Reports
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
                <div class="panel-heading">Reports</div>
                <table class="table">
                    <tbody>
                        @if(empty($reports))
                        <tr>
                            <td>No reports</td>
                        </tr>
                        @endif
                        @if(!empty($reports))
                        @foreach($reports as $report)
                        <tr>
                            <td>#{{ $report->report_id }}</td>
                            <td>{{ $report->name }}</td>
                            <td>
                                {{ (strlen($report->details) > 73) ?
                                substr($report->details, 0, 70).'...' : $report->details; }}
                            </td>
                            <td>
                                <a href="/control/dashboard?sort=reports&view={{ $report->report_id }}">
                                    View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </section>
    </section>
</section>
@stop

@section('js')

@stop
