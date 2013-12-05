@extends('templates.master')

@section('title')
Home
@stop

@section('internalCss')
<link href="/assets/css/site/home.style.css" rel="stylesheet">
<link href="/assets/css/plugins/postcreator.style.css" rel="stylesheet">
<link href="/assets/css/plugins/poststream.style.css" rel="stylesheet">
<link href="/assets/css/plugins/chosen.css" rel="stylesheet">
<link href="/assets/css/plugins/datepicker.css" rel="stylesheet">
@stop

@section('content')

<section class="message-holder"><span></span></section>
<section class="row">
    <section class="col-md-3">
        <!-- Left Sidebar -->
        <section class="user-details-holder well">
            {{ Helper::avatar(50, "normal", "pull-left") }}

            <section class="user-details-content pull-left">
                @if(Auth::user()->account_type == 1)
                <a href="#">Hi, {{ Auth::user()->salutation.' '.Auth::user()->lastname }}</a>
                <section class="user-type">Teacher</section>
                @endif
                @if(Auth::user()->account_type == 2)
                <a href="#">Hi, {{ Auth::user()->name }}</a>
                <section class="user-type">Student</section>
                @endif
            </section>
            <section class="clearfix"></section>
        </section>

        <section class="user-groups-holder">
            <section class="section-title-holder">
                <span>Groups</span>
                <section class="dropdown pull-right">
                    <a data-toggle="dropdown" href="#" id="group_options">
                        <i class="fa fa-plus-circle"></i>
                    </a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                        @if(Auth::user()->account_type == 1)
                        <li><a href="#" id="show_create_group">Create</a></li>
                        @endif
                        <li><a href="#" id="show_join_group">Join</a></li>
                    </ul>
                </section>
            </section>
            <ul class="nav nav-pills nav-stacked">
                @if(!empty($groups))
                @foreach($groups as $group)
                <li><a href="/groups/{{ $group->group_id }}">{{ $group->group_name }}</a></li>
                @endforeach
                @else
                <li>No Groups Found</li>
                @endif
            </ul>
        </section>

    </section>
    <section class="col-md-9">

        <section class="modal fade" id="the_modal" tabindex="-1" role="dialog"
        aria-labelledby="the_modal_label" aria-hidden="true"></section>

        <!-- Main Content -->
        @include('plugins.postcreator')

        @include('plugins.poststream')
    </section>
</section>

@stop

@section('js')
<script type="text/javascript" src="/assets/js/plugins/jquery.form.min.js"></script>
<script src="/assets/js/plugins/chosen.js"></script>
<script src="/assets/js/plugins/expanding.js"></script>
<script src="/assets/js/plugins/bootstrap-datepicker.js"></script>

<script src="/assets/js/plugins/postcreator.js"></script>
<script src="/assets/js/sitefunc/comment.creator.js"></script>
<script src="/assets/js/sitefunc/poststream.js"></script>
<script src="/assets/js/plugins/groups.js"></script>
@if(isset($quiz))
<script>
(function($) {
    $('#quiz .post-recipients').chosen();

    var nowTemp = new Date();
    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

    var checkin = $('#quiz_due_date').datepicker({
        onRender: function(date) {
            return date.valueOf() < now.valueOf() ? 'disabled' : '';
        },
        format : 'yyyy-mm-dd'
    }).on('changeDate', function(ev) {
        checkin.hide();
    }).data('datepicker');
})(jQuery);
</script>
@endif
@stop
