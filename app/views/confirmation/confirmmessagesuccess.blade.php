@extends('templates.unauthorized')
@section('css')
<style>
.confirm-message-success .success-message-wrapper { margin: auto; width: 700px; }
.confirm-message-success .success-message-wrapper h1 { margin-bottom: 30px; }
.confirm-message-success .success-message-wrapper .lead { margin: 0; }
</style>
@stop
@section('content')
<div class="content confirm-message-success">
    <div class="success-message-wrapper">
        <h1>Check yo mail now!</h1>

        <p class="lead">We already sent your confirmation mail!</p>
        <p class="lead">Please check it now so you can start learning and
        be awesome!</p>
    </div>
</div>
@stop
