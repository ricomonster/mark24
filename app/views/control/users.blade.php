@extends('templates.admin')

@section('title')
Dashboard - Users
@stop

@section('internalCss')

@stop

@section('content')
<section class="container">
    <section class="row">
        <section class="col-md-3">
            <ul class="nav nav-stacked nav-pills">
                <li><a href="?sort=stats">Stats</a></li>
                <li class="active"><a href="?sort=users">Users</a></li>
                <li><a href="?sort=groups">Groups</a></li>
                <li><a href="?sort=posts">Posts</a></li>
                <li><a href="?sort=the-forum">The Forum</a></li>
                <li><a href="?sort=the-library">The Library</a></li>
                <li><a href="?sort=reports">Reports</a></li>
            </ul>
        </section>
        <section class="col-md-9">
            <section class="well">
                <h3>Users</h3>
                <hr/>
                <table class="table">
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td width="60">{{ Helper::avatar(60, "normal", null, $user->id) }}</td>
                            <td>
                                {{ $user->salutation.' '.$user->name }}
                                ({{ $user->username }})
                            </td>
                            <td>
                                @if($user->account_type == 1)
                                Teacher
                                @endif
                                @if($user->account_type == 2)
                                Student
                                @endif
                            </td>
                            <td>
                                <a href="mailto:{{ $user->email }}">{{ $user->email }}</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        </section>
    </section>
</section>
@stop

@section('js')

@stop
