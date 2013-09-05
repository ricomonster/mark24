@extends('templates.master')

@section('title')
Settings - Password
@stop

@section('internalCss')
<link rel="stylesheet" type="text/css" href="/assets/css/site/settings.style.css">
<style>
.change-password-wrapper { padding: 0; }
.change-password-wrapper .page-header,
.change-password-wrapper form { padding: 0 19px 19px; }
</style>
@stop

@section('content')
<div class="row">
    <div class="settings-nav-wrapper col-md-3">
        <ul class="nav nav-stacked nav-pills">
            <li><a href="/settings">Account</a></li>
            <li class="active"><a href="/settings/password">Password</a></li>
            <li><a href="/settings/privacy">Privacy</a></li>
        </ul>
    </div>
    <div class="col-md-9">
        <div class="change-password-wrapper well">
            <div class="page-header"><h3>Password</h3></div>
            {{ Form::open(array('url'=>'ajax/users/change-password')) }}
                <div class="form-group">
                    <label class="current-password">Current Password</label>
                    <input type="password" name="current-password"
                    id="current_password" class="form-control">
                </div>

                <div class="form-group">
                    <label class="new-password">New Password</label>
                    <input type="password" name="new-password"
                    id="new_password" class="form-control">
                </div>

                <div class="form-group">
                    <label class="confirm-new-password">Confirm New Password</label>
                    <input type="password" name="confirm-new-password"
                    id="confirm_new_password" class="form-control">
                </div>

                <button type="submit" id="submit_password_change" class="btn btn-primary">
                    Change Password
                </button>
            {{ Form::close() }}
        </div>
    </div>
</div>
@stop

@section('js')
<script type="text/javascript" src="/assets/js/plugins/jquery.form.min.js"></script>
<script>
(function($) {
    $('#submit_password_change').on('click', function(e) {
        var $this = $(this);

        

        e.preventDefault();
    });
})(jQuery);
</script>
@stop
