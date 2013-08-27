@extends('templates.master')

@section('title')
Home
@stop

@section('internalCss')
<link href="/assets/css/site/home.style.css" rel="stylesheet">
<link href="/assets/css/plugins/postcreator.style.css" rel="stylesheet">
<link href="/assets/css/plugins/poststream.style.css" rel="stylesheet">
<style type="text/css">

</style>
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
                @foreach($groups as $group)
                <li><a href="/groups/{{ $group->group_id }}">{{ $group->group_name }}</a></li>
                @endforeach
            </ul>
        </div>

    </div>
    <div class="col-md-9">

        <div class="modal fade" id="the_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"></div>

        <!-- Main Content -->
        @include('plugins.postcreator')

        @include('plugins.poststream')
    </div>
</div>

@stop

@section('js')
<script>
(function($) {
    $('#post_creator_options a').click(function (e) {
        e.preventDefault();
        $(this).tab('show');
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

})(jQuery)
</script>
@stop
