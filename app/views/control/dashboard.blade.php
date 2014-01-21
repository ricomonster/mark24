@extends('templates.admin')

@section('title')
Dashboard - Stats
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
                <li class="active"><a href="?sort=stats">Stats</a></li>
                <li><a href="?sort=users">Users</a></li>
                <li><a href="?sort=groups">Groups</a></li>
                <li><a href="?sort=posts">Posts</a></li>
                <li><a href="?sort=the-forum">The Forum</a></li>
                <li><a href="?sort=the-library">The Library</a></li>
                <li><a href="?sort=reports">Reports</a></li>
            </ul>
        </section>
        <section class="col-md-9">
            <div class="panel panel-default">
                <!-- Default panel contents -->
                <div class="panel-heading">Stats</div>
                <table class="table">
                    <tbody>
                        <tr>
                            <th colspan="10">Users</th>
                        </tr>
                        <tr>
                            <th>Teachers</th>
                            <td>{{ $stats['teachers'] }}</td>
                            <th>Students</th>
                            <td>{{ $stats['students'] }}</td>
                            <th>Total</th>
                            <td colspan="5">{{ $stats['users'] }}</td>
                        </tr>
                        <tr>
                            <th colspan="10">Groups</th>
                        </tr>
                        <tr>
                            <th>Number of Groups</th>
                            <td colspan="9">{{ $stats['groups'] }}</td>
                        </tr>
                        <tr>
                            <th colspan="10">Posts</th>
                        </tr>
                        <tr>
                            <th>Notes</th>
                            <td>{{ $stats['notes'] }}</td>
                            <th>Alerts</th>
                            <td>{{ $stats['alerts'] }}</td>
                            <th>Assignments</th>
                            <td>{{ $stats['assignments'] }}</td>
                            <th>Quizzes</th>
                            <td>{{ $stats['quizzes'] }}</td>
                            <th>Total</th>
                            <td>{{ $stats['posts'] }}</td>
                        </tr>
                        <tr>
                            <th colspan="10">The Forum</th>
                        </tr>
                        <tr>
                            <th>Threads</th>
                            <td>{{ $stats['threads'] }}</td>
                            <th>Replies</th>
                            <td colspan="5">{{ $stats['replies'] }}</td>
                        </tr>
                        <tr>
                            <th colspan="10">The Library</th>
                        </tr>
                        <tr>
                            <th>Files</th>
                            <td colspan="8">300</td>
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
