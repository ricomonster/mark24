@extends('templates.master')

@section('title')
Settings
@stop

@section('internalCss')
<link rel="stylesheet" type="text/css" href="/assets/css/site/settings.style.css">
<style>
.personal-information-wrapper,
.story-profile-wrapper { border-bottom: none; margin-bottom: 0; }
form .form-group .error-block { display: none; }
</style>
@stop

@section('content')
<div class="row">
    <div class="settings-nav-wrapper col-md-3">
        <ul class="nav nav-stacked nav-pills">
            <li><a href="/settings">Account Settings</a></li>
            <li class="active"><a href="/settings/profile">Profile Settings</a></li>
            <!-- <li><a href="/settings/password">Password</a></li> -->
            <!-- <li><a href="/settings/privacy">Privacy</a></li> -->
        </ul>
    </div>
    <div class="col-md-9">
        <div class="personal-information-wrapper well">
            <h3>Personal Information</h3>
            {{ Form::open(array('url'=>'ajax/users/update-personal-info', 'method'=>'put', 'class'=>'personal-information-form')) }}
                @if(Auth::user()->account_type == 1)
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
                @endif

                @if(Auth::user()->account_type == 2)
                <div class="form-group">
                    <label for="firstname">First Name</label>
                    <input type="text" name="firstname" class="form-control"
                    value="{{ Auth::user()->firstname }}">
                </div>

                <div class="form-group">
                    <label for="lastname">Last Name</label>
                    <input type="text" name="lastname" class="form-control"
                    value="{{ Auth::user()->lastname }}">
                </div>
                @endif

                <button type="submit" id="submit_personal_info" class="btn btn-primary">
                    Save Personal Info
                </button>
            {{ Form::close() }}
        </div>
        <div class="story-profile-wrapper well">
            <h3>Story</h3>
            {{ Form::open(array('url' => 'ajax/users/update-story', 'class' => 'update-story-form')) }}
                <div class="form-group">
                    <label for="tagline">Tagline</label>
                    <span class="help-block error-block"></span>
                    <input type="text" name="tagline" class="form-control tagline"
                    placeholder="What's your tagline?" value="{{ Auth::user()->tagline }}">
                    <p class="help-block">Example: "I'm the best of both worlds!"</p>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <span class="help-block error-block"></span>
                    <textarea name="description" class="form-control description"
                    placeholder="Describe your self!">{{ Auth::user()->description }}</textarea>
                    <p class="help-block">Example: "I'm just a big fat boy."</p>
                </div>

                <div class="button-controls">
                    <button type="submit" class="btn btn-primary" id="update_story">
                        Save
                    </button>
                </div>
            {{ Form::close() }}
        </div>
        <div class="places-profile-wrapper well">
            <h3>Places</h3>
            {{ Form::open(array('url' => 'ajax/users/update-places', 'class' => 'update-places-form')) }}
                <div class="form-group">
                    <label for="current">Current Place</label>
                    <span class="help-block error-block"></span>
                    <input type="text" name="current" class="form-control current"
                    placeholder="Current Place" value="{{ Auth::user()->current_place }}">
                </div>
                <div class="form-group">
                    <label for="current">Hometown</label>
                    <span class="help-block error-block"></span>
                    <input type="text" name="hometown" class="form-control hometown"
                    placeholder="Hometown" value="{{ Auth::user()->hometown }}">
                </div>
                <div class="form-group">
                    <label for="country">Country</label>
                    <span class="help-block error-block"></span>
                    <select name="country" class="form-control country">
                        <option value="" selected>-- Select Country --</option>
                        @foreach($countries as $country)
                        <option value="{{ $country }}"
                        {{ (Auth::user()->country == $country) ?
                        'selected' : null; }}>{{ $country }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="button-controls">
                    <button type="submit" class="btn btn-primary" id="update_places">
                        Save
                    </button>
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@stop

@section('js')
<script>
(function($) {
    var error = 0;
    var messageHolder = $('.message-holder');

    $('#submit_personal_info').on('click', function(e) {
        var $this = $(this);

        $this.attr('disabled');
        $('.message-holder').show().find('span').text('Saving...');

        $.ajax({
            type : 'put',
            data : $('.personal-information-form').serialize(),
            url : $('.personal-information-form').attr('action'),
            dataType : 'json'
        }).done(function(response) {
            if(response.error) {
                $('.message-holder').show().find('span').text('An error occured...');
                if(response.field == 'email') {
                    $this.removeAttr('disabled');

                    $('#email').parent().addClass('has-error').find('.help-block')
                        .text(response.message);
                }
            } else {
                $this.removeAttr('disabled');
                $('.message-holder').show().find('span')
                    .text('You have successfully updated your profile');
                $('.personal-information-form .form-group').removeClass('has-error')
                    .find('.help-block').hide();
            }

            setTimeout(function() {
                $('.message-holder').hide();
            }, 5000);
        })

        e.preventDefault();
    });

    $("#update_story").on('click', function(e) {
        e.preventDefault();
        error = 0;

        var form        = $('.update-story-form');
        var tagline     = $('.tagline');
        var description = $('.description');

        messageHolder.show()
            .find('span')
            .text('Updating...');
        // ajax
        $.ajax({
            type        : 'post',
            url         : form.attr('action'),
            data        : form.serialize(),
            dataType    : 'json'
        }).done(function(response) {
            if(response.error) {
                messageHolder.show()
                    .find('span')
                    .text('There was an error. Please try again later.');
            }

            if(!response.error) {
                messageHolder.show()
                    .find('span')
                    .text('You have successfully updated your story.');
            }

            setTimeout(function() {
                messageHolder.fadeOut();
            }, 5000);
        });
    });

    $('#update_places').on('click', function(e) {
        e.preventDefault();

        error = 0;
        var form = $('.update-places-form');
        var current = $('.current');
        var hometown = $('.hometown');
        var country = $('.country');

        // validate
        messageHolder.show()
            .find('span')
            .text('Updating...');

        // ajax
        $.ajax({
            type        : 'post',
            url         : form.attr('action'),
            data        : form.serialize(),
            dataType    : 'json'
        }).done(function(response) {
            if(response.error) {
                messageHolder.show()
                    .find('span')
                    .text('There was an error. Please try again later.');
            }

            if(!response.error) {
                messageHolder.show()
                    .find('span')
                    .text('You have successfully updated your places.');
            }

            setTimeout(function() {
                messageHolder.fadeOut();
            }, 5000);
        });
    })
})(jQuery);
</script>
@stop
