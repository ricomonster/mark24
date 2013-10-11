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
.form-group .alert { display: none; padding: 8px 13px; }
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
                    <div class="alert"></div>
                    <input type="password" name="current-password"
                    id="current_password" class="form-control">
                </div>

                <div class="form-group">
                    <label class="new-password">New Password</label>
                    <div class="alert"></div>
                    <input type="password" name="new-password"
                    id="new_password" class="form-control">
                </div>

                <div class="form-group">
                    <label class="confirm-new-password">Confirm New Password</label>
                    <div class="alert"></div>
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
    var error = 0;

    $('#submit_password_change').on('click', function(e) {
        var $this = $(this);
        var currentPassword     = $('#current_password');
        var newPassword         = $('#new_password');
        var confirmNewPassword  = $('#confirm_new_password');

        error = 0;

        // check if current password is empty or not
        if(currentPassword.val() == '' || currentPassword.val().length == 0) {
            // show error message
            currentPassword.parent().addClass('has-error')
                .find('.alert').addClass('alert-danger')
                .text('What is your current password?').show();

            return false;
        }

        // current password field is not empty
        if(currentPassword.val() != '' || currentPassword.val().length != 0) {
            currentPassword.parent().removeClass('has-error')
                .find('.alert').hide();

            // validate new password and confirm new password
            // validae first the new password
            if(newPassword.val() == '' || newPassword.val().length == 0) {
                // show error message
                newPassword.parent().addClass('has-error')
                    .find('.alert').addClass('alert-danger')
                    .text('What is your new password?').show();

                return false;
            }

            // if new password has no problems / errors
            if(newPassword.val() != '' || newPassword.val().length != 0) {
                newPassword.parent().removeClass('has-error')
                    .find('.alert').hide();

                // validate confirm new password
                if(newPassword.val() != confirmNewPassword.val()) {
                    newPassword.parent().addClass('has-error');

                    confirmNewPassword.parent().addClass('has-error')
                        .find('.alert').addClass('alert-danger')
                        .text('Both passwords are not the same.').show();

                    return false;
                }

                if(newPassword.val() == confirmNewPassword.val()) {
                    newPassword.parent().removeClass('has-error');

                    confirmNewPassword.parent().removeClass('has-error')
                        .find('.alert').hide();
                }
            }
        }

        // validate the current password if
        // it's equal what's in the database


        e.preventDefault();
    });
})(jQuery);
</script>
@stop
