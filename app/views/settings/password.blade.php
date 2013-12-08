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
<div class="message-holder"><span></span></div>
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
            {{ Form::open(array('url'=>'ajax/users/change-password', 'id' => 'change_password_form')) }}
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

        $('.message-holder').show().find('span').text('Saving...');

        $.ajax({
            type        : 'post',
            url         : '/ajax/settings/change-password',
            data        : $('#change_password_form').serialize(),
            dataType    : 'json',
            async       : false
        }).done(function(response) {
            // there are errors
            if(response.error) {
                // show the error messages
                if(response.messages.current_password) {
                    currentPassword.parent().addClass('has-error');
                    currentPassword.siblings('.alert').show()
                        .addClass('alert-danger').text(response.messages.current_password);
                }

                if(!response.messages.current_password) {
                    currentPassword.parent().removeClass('has-error');
                    currentPassword.siblings('.alert').hide();
                }

                if(response.messages.new_password) {
                    newPassword.parent().addClass('has-error');
                    newPassword.siblings('.alert').show()
                        .addClass('alert-danger').text(response.messages.new_password);
                }

                if(!response.messages.new_password) {
                    newPassword.parent().removeClass('has-error');
                    newPassword.siblings('.alert').hide();
                }

                if(response.messages.confirm_password) {
                    confirmNewPassword.parent().addClass('has-error');
                    confirmNewPassword.siblings('.alert').show()
                        .addClass('alert-danger').text(response.messages.confirm_password);
                }

                if(!response.messages.confirm_password) {
                    confirmNewPassword.parent().removeClass('has-error');
                    confirmNewPassword.siblings('.alert').hide();
                }

                $('.message-holder').show().find('span').text('There are errors. Please fix it.');
                setInterval(function() {
                    $('.message-holder').fadeOut();
                }, 3000);
            }

            // no errors
            if(!response.error) {
                // hide the error
                currentPassword.parent().removeClass('has-error');
                currentPassword.siblings('.alert').hide();

                newPassword.parent().removeClass('has-error');
                newPassword.siblings('.alert').hide();

                confirmNewPassword.parent().removeClass('has-error');
                confirmNewPassword.siblings('.alert').hide();

                $('#change_password_form')[0].reset();
                $('.message-holder').show().find('span').text('Password successfully changed.');

                setInterval(function() {
                    $('.message-holder').fadeOut();
                }, 3000);
            }
        });

        e.preventDefault();
    });
})(jQuery);
</script>
@stop
