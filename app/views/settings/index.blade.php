@extends('templates.master')

@section('title')
Settings
@stop

@section('internalCss')
<link rel="stylesheet" type="text/css" href="/assets/css/site/settings.style.css">
<style>
.account-details-wrapper { border-bottom: none; margin-bottom: 0; }
.change-password-wrapper form .form-group .alert { display: none; padding: 8px 13px; }
</style>
@stop

@section('content')
<div class="row">
    <div class="settings-nav-wrapper col-md-3">
        <ul class="nav nav-stacked nav-pills">
            <li class="active"><a href="/settings">Account Settings</a></li>
            <li><a href="/settings/profile">Profile Settings</a></li>
            <!-- <li><a href="/settings/password">Password</a></li> -->
            <!-- <li><a href="/settings/privacy">Privacy</a></li> -->
        </ul>
    </div>
    <div class="col-md-9">
        <div class="user-avatar-wrapper well">
            <h3>User Photo</h3>
            <div class="current-avatar-wrapper pull-left">
                <img src="/assets/images/loader_medium.gif" width="140" class="image-loader-gif">
                {{ Helper::avatar(140, "large", "current-user-avatar") }}
                <span class="current-avatar-subtext subtext" style="display: block;">Your Current Photo</span>
            </div>
            <div class="choose-avatar-wrapper pull-left">
                {{ Form::open(array('url' => 'ajax/users/upload-photo', 'files'=>true, 'class'=>'avatar-uploader-form')) }}
                    <input type="file" name="avatar-file" id="avatar_file" accept="image/*">
                {{ Form::close() }}

                <span class="text-muted">Or select one of these.</span>

                <ul class="predefined-avatar-wrapper">
                    <li>
                        <a href="#" class="predefined-avatar" data-avatar="1">
                            <img src="/assets/defaults/avatar/default_avatar.png">
                        </a>
                    </li>
                    <li>
                        <a href="#" class="predefined-avatar" data-avatar="2">
                            <img src="/assets/defaults/avatar/default_avatar_2.png">
                        </a>
                    </li>
                    <li>
                        <a href="#" class="predefined-avatar" data-avatar="3">
                            <img src="/assets/defaults/avatar/default_avatar_3.png">
                        </a>
                    </li>
                    <li>
                        <a href="#" class="predefined-avatar" data-avatar="4">
                            <img src="/assets/defaults/avatar/default_avatar_4.png">
                        </a>
                    </li>
                </ul>
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="account-details-wrapper well">
            <h3>Account Details</h3>
            {{ Form::open(array('url'=>'ajax/users/update-personal-info', 'method'=>'put', 'class'=>'personal-information-form')) }}
                <div class="form-group">
                    <label for="email">Email</label>
                    <span class="help-block"></span>
                    <input type="email" name="email" id="email" class="form-control"
                    value="{{ Auth::user()->email }}">
                </div>

                <button type="submit" id="submit_personal_info" class="btn btn-primary">
                    Save Personal Info
                </button>
            {{ Form::close() }}
        </div>

        <div class="change-password-wrapper well">
            <h3>Password</h3>
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

    $('#avatar_file').on('change', function() {

        $('.message-holder').show().find('span').text('Saving...');
        // change the image to a rotating gif
        $('.image-loader-gif').show();
        $('.current-user-avatar').hide();

        $('.avatar-uploader-form').ajaxForm({
            url : $(this).attr('action'),
            dataType : 'json',
            success : function(response) {
                if(response.error) {
                    $('.image-loader-gif').hide();
                    $('.current-user-avatar').show();
                } else {
                    $('.image-loader-gif').hide();
                    $('.current-user-avatar').attr('src', response.userAvatar)
                        .show();
                    $('.header-avatar').attr('src', response.userAvatar);
                }

                $('.message-holder').hide();
            }
        }).submit();
    });

    // predefined avatar
    $('.predefined-avatar').on('click', function(e) {
        var $this = $(this);

        $('.message-holder').show().find('span').text('Saving...');

        $('.image-loader-gif').show();
        $('.current-user-avatar').hide();

        $.ajax({
            type : 'post',
            url : '/ajax/settings/predefined-avatar',
            data : { avatar : $this.data('avatar') },
            dataType : 'json',
            async : false
        }).done(function(response) {
            // hide loading image
            // replace the current user avatar
            // replace also the ones in the header
            if(response.error) {
                $('.image-loader-gif').hide();
                $('.current-user-avatar').show();
            } else {
                $('.image-loader-gif').hide();
                $('.current-user-avatar').attr('src', response.avatar).show();
                $('.header-avatar').attr('src', response.avatar);
            }

            $('.message-holder').hide();
        });

        e.preventDefault();
    });

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
