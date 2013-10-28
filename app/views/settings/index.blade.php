@extends('templates.master')

@section('title')
Settings
@stop

@section('internalCss')
<link rel="stylesheet" type="text/css" href="/assets/css/site/settings.style.css">
@stop

@section('content')
<div class="message-holder"><span></span></div>
<div class="row">
    <div class="settings-nav-wrapper col-md-3">
        <ul class="nav nav-stacked nav-pills">
            <li class="active"><a href="/settings">Account</a></li>
            <li><a href="/settings/password">Password</a></li>
            <li><a href="/settings/privacy">Privacy</a></li>
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

        <div class="personal-information-wrapper well">
            <h3>Personal Information</h3>
            {{ Form::open(array('url'=>'ajax/users/update-personal-info', 'method'=>'put', 'class'=>'personal-information-form')) }}
                <div class="form-group">
                    <label for="email">Email</label>
                    <span class="help-block"></span>
                    <input type="email" name="email" id="email" class="form-control"
                    value="{{ Auth::user()->email }}">
                </div>

                <div class="row">
                    <div class="form-group col-md-2">
                        <label for="salutation">Title</label>
                        <select name="salutation" class="form-control">
                            <option value="Mr." <?php echo (Auth::user()->salutation == 'Mr.') ? 'selected' : null; ?>>Mr.</option>
                            <option value="Mrs." <?php echo (Auth::user()->salutation == 'Mrs.') ? 'selected' : null; ?>>Mrs.</option>
                            <option value="Ms." <?php echo (Auth::user()->salutation == 'Ms.') ? 'selected' : null; ?>>Ms.</option>
                            <option value="Dr." <?php echo (Auth::user()->salutation == 'Dr.') ? 'selected' : null; ?>>Dr.</option>
                        </select>
                    </div>

                    <div class="form-group col-md-5">
                        <label for="firstname">First Name</label>
                        <input type="text" name="firstname" class="form-control"
                        value="{{ Auth::user()->firstname }}">
                    </div>

                    <div class="form-group col-md-5">
                        <label for="lastname">Last Name</label>
                        <input type="text" name="lastname" class="form-control"
                        value="{{ Auth::user()->lastname }}">
                    </div>
                </div>

                <button type="submit" id="submit_personal_info" class="btn btn-primary">
                    Save Personal Info
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
    $('#avatar_file').on('change', function() {
<<<<<<< HEAD

        $('.message-holder').show().find('span').text('Saving...');
=======
>>>>>>> 949bee8a5ef532de5ec61f81001fc81ad0ed5599
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
<<<<<<< HEAD
                    $('.header-avatar').attr('src', response.userAvatar);
                }

                $('.message-holder').hide();
=======
                }
>>>>>>> 949bee8a5ef532de5ec61f81001fc81ad0ed5599
            }
        }).submit();
    });

    $('#submit_personal_info').on('click', function(e) {
        var $this = $(this);

        $this.attr('disabled');

        $.ajax({
            type : 'put',
            data : $('.personal-information-form').serialize(),
            url : $('.personal-information-form').attr('action'),
            dataType : 'json'
        }).done(function(response) {
            if(response.error) {
                if(response.field == 'email') {
                    $this.removeAttr('disabled');

                    $('#email').parent().addClass('has-error').find('.help-block')
                        .text(response.message);
                }
            } else {
                $this.removeAttr('disabled');

                $('.personal-information-form .form-group').removeClass('has-error')
                    .find('.help-block').hide();
            }
        })

        e.preventDefault();
    });

    // predefined avatar
    $('.predefined-avatar').on('click', function(e) {
        var $this = $(this);

<<<<<<< HEAD
        $('.message-holder').show().find('span').text('Saving...');

=======
>>>>>>> 949bee8a5ef532de5ec61f81001fc81ad0ed5599
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
<<<<<<< HEAD
                $('.header-avatar').attr('src', response.avatar);
            }

            $('.message-holder').hide();
        });

        e.preventDefault();
=======
            }
        })
>>>>>>> 949bee8a5ef532de5ec61f81001fc81ad0ed5599
    })
})(jQuery);
</script>
@stop
