@extends('templates.unauthorized')
@section('css')
<style>
.confirm-account-success .success-message-wrapper { margin: auto; width: 700px; }
.confirm-account-success .success-message-wrapper h1 { margin-bottom: 30px; }
.confirm-account-success .success-message-wrapper .lead { margin: 0; }
</style>
@stop
@section('content')
<div class="content confirm-account-success">
    <div class="success-message-wrapper">
        <h1>Hooray!</h1>

        <p class="lead">You have successfully confirmed your email!</p>
        <p class="lead">You can now start by logging in!</p>
    </div>
</div>
@stop