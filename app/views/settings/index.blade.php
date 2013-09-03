@extends('templates.master')

@section('title')
Settings
@stop

@section('internalCss')
<style type="text/css">
.settings-nav-wrapper .nav li { margin: 0; }
.settings-nav-wrapper .nav li.active a { background-color: #f3f5f7; color: #2a6496; }
.settings-nav-wrapper .nav li a:hover { background-color: #f3f5f7; }
.settings-nav-wrapper .nav li a {
    background-color: #ffffff;
    border: 1px solid #dfe4e8 !important;
    border-top: 0 !important;
    border-radius: 0;
}

.user-avatar-wrapper .current-avatar-wrapper { margin-right: 60px; }
.user-avatar-wrapper .current-avatar-wrapper img { margin-bottom: 10px; }
.user-avatar-wrapper .current-avatar-wrapper .current-avatar-subtext { text-align: center; }

.user-avatar-wrapper .choose-avatar-wrapper .avatar-uploader-form { margin-bottom: 10px; }

.user-avatar-wrapper .choose-avatar-wrapper .predefined-avatar-wrapper { margin-top: 10px; }

.image-loader-gif { display: none; }
</style>
@stop

@section('content')
<div class="row">
    <div class="settings-nav-wrapper col-md-3">
        <ul class="nav nav-stacked nav-pills">
            <li class="active"><a href="">Account</a></li>
            <li><a href="">Password</a></li>
            <li><a href="">Privacy</a></li>
        </ul>
    </div>
    <div class="col-md-9">
        <div class="user-avatar-wrapper well">
            <h3>User Photo</h3>
            <div class="current-avatar-wrapper pull-left">
                <img src="/assets/images/loader_medium.gif" width="140" class="image-loader-gif">
                <img src="/assets/images/default_avatar.png" width="140" class="current-user-avatar">
                <span class="current-avatar-subtext subtext">Your Current Photo</span>
            </div>
            <div class="choose-avatar-wrapper pull-left">
                {{ Form::open(array('url' => 'ajax/users/upload-photo', 'files'=>true, 'class'=>'avatar-uploader-form')) }}
                    <input type="file" name="avatar-file" id="avatar_file" accept="image/*">
                {{ Form::close() }}

                <span class="subtext">Or select one of the shits.</span>

                <div class="predefined-avatar-wrapper">
                    Predifined shits here.
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
    </div>
</div>
@stop

@section('js')
<script type="text/javascript" src="/assets/js/plugins/jquery.form.min.js"></script>
<script>
(function($) {
    $('#avatar_file').on('change', function() {
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
                }
            }
        }).submit();
    })
})(jQuery);
</script>
@stop
