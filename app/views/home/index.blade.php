@extends('templates.master')

@section('title')
Home
@stop

@section('internalCss')
<link href="/assets/css/site/home.style.css" rel="stylesheet">
<link href="/assets/css/plugins/postcreator.style.css" rel="stylesheet">
<link href="/assets/css/plugins/poststream.style.css" rel="stylesheet">
<link href="/assets/css/plugins/chosen.css" rel="stylesheet">
@stop

@section('content')

<div class="row">
    <div class="col-md-3">
        <!-- Left Sidebar -->
        <div class="user-details-holder well">
            <img src="/assets/images/anon.png" width="50" class="img-rounded pull-left">
            <div class="user-details-content pull-left">
                @if(Auth::user()->account_type == 1)
                <a href="#">Hi, {{ Auth::user()->salutation.' '.Auth::user()->lastname }}</a>
                <div class="user-type">Teacher</div>
                @else
                <a href="#">Hi, {{ Auth::user()->name }}</a>
                <div class="user-type">Student</div>
                @endif
            </div>
            <div class="clearfix"></div>
        </div>

        <div class="user-groups-holder">
            <div class="section-title-holder">
                <span>Groups</span>
                <div class="dropdown pull-right">
                    <a data-toggle="dropdown" href="#" id="group_options"><i class="icon-plus-sign"></i></a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                        @if(Auth::user()->account_type == 1)
                        <li><a href="#" id="show_create_group">Create</a></li>
                        @endif
                        <li><a href="#" id="show_join_group">Join</a></li>
                    </ul>
                </div>
            </div>
            <ul class="nav nav-pills nav-stacked">
                @if(!empty($groups))
                @foreach($groups as $group)
                <li><a href="/groups/{{ $group->group_id }}">{{ $group->group_name }}</a></li>
                @endforeach
                @else
                <li>No Groups Found</li>
                @endif
            </ul>
        </div>

    </div>
    <div class="col-md-9">

        <div class="modal fade" id="the_modal" tabindex="-1" role="dialog"
        aria-labelledby="the_modal_label" aria-hidden="true"></div>

        <!-- Main Content -->
        @include('plugins.postcreator')

        @include('plugins.poststream')
    </div>
</div>

@stop

@section('js')
<script src="/assets/js/plugins/chosen.js"></script>
<script src="/assets/js/plugins/expanding.js"></script>
<script src="/assets/js/plugins/postcreator.js"></script>
<script>
(function($) {
    // Join Group Modal
    $('#show_join_group').on('click', function(e) {
        $('#the_modal').modal('show');
        $.get('/ajax/modal/show_join_group', function(response) {
            $('#the_modal').html(response);
        });

        e.preventDefault();
    });

    $(document).on('click', 'button#trigger_join_group', function(e) {
        var thisButton = $(this);

        thisButton.attr('disabled');
        $('.join-group-modal .form-group').removeClass('has-error');
        $('.join-group-modal .alert').hide();

        $.ajax({
            type        : 'post',
            url         : '/ajax/modal/join_group',
            data        : $('.join-group-modal').serialize(),
            dataType    : 'json',
            async       : false
        }).done(function(response) {
            if(response.error) {
                // show error message
                $('#group_code').parent().addClass('has-error');
                $('#group_code').siblings('.alert').addClass('alert-danger')
                    .html(response.message).show();

                thisButton.removeAttr('disabled');

                return false;
            }

            // no error get LZ link so page will redirect
            window.location.href = response.lz_link;
            return false;
        });

        e.preventDefault();
    });

    // Create Group Modal
    $('#show_create_group').on('click', function(e) {
        $('#the_modal').modal('show');
        $.get('/ajax/modal/show_create_group', function(response) {
            $('#the_modal').html(response);
        });

        e.preventDefault();
    });

    $(document).on('click', 'button#trigger_create_group', function() {
        var thisButton = $(this);
        // reset status
        $('.create-group-modal .form-group').removeClass('has-error');
        $('.create-group-modal .alert').hide();

        thisButton.attr('disabled');

        $.ajax({
            type        : 'post',
            url         : '/ajax/modal/create_group',
            data        : $('.create-group-modal').serialize(),
            dataType    : 'json',

            success : function(response) {
                // check if there's an error
                if(response.error === true) {
                    // set up the error message to show on the modal
                    if(response.messages.groupName) {
                        // with error
                        $('#group_name').parent().addClass('has-error');
                        $('#group_name').siblings('.alert').addClass('alert-danger')
                            .html(response.messages.groupName).show();
                    } else {
                        // clean up error state
                        $('#group_name').parent().removeClass('has-error');
                        $('#group_name').siblings('.alert').removeClass('alert-danger')
                            .empty().hide();
                    }

                    if(response.messages.groupSize) {
                        // with error
                        $('#group_size').parent().addClass('has-error');
                        $('#group_size').siblings('.alert').addClass('alert-danger')
                            .html(response.messages.groupSize).show();
                    } else {
                        // clean up error state
                        $('#group_size').parent().removeClass('has-error');
                        $('#group_size').siblings('.alert').removeClass('alert-danger')
                            .empty().hide();
                    }

                    if(response.messages.groupDescription) {
                        // with error
                        $('#group_description').parent().addClass('has-error');
                        $('#group_description').siblings('.alert').addClass('alert-danger')
                            .html(response.messages.groupDescription).show();
                    } else {
                        // clean up error state
                        $('#group_description').parent().removeClass('has-error');
                        $('#group_description').siblings('.alert').removeClass('alert-danger')
                            .empty().hide();
                    }

                    thisButton.removeAttr('disabled');
                }

                // no error get LZ link so page will redirect
                window.location.href = response.lz_link;
            }
        })
    });
    
    // postcreator js
    
})(jQuery)
</script>
@stop
