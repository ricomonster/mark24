@extends('templates.master')

@section('title')
Assignment Sheet
@stop

@section('internalCss')
<style type="text/css">
.assignment-manager-wrapper .assignment-details-wrapper { padding: 0; }
.assignment-details-wrapper .assignment-details { margin: 0; padding: 15px; }
.assignment-details-wrapper .assignment-details .assignment-sheet-icon { color: #8c94a7; float: left; font-size: 24px; }
.assignment-details-wrapper .assignment-details .content-details { float: left; margin-left: 10px; }
.assignment-details-wrapper .assignment-details .content-details p { font-size: 16px; margin: 0; padding: 0; }
.assignment-details-wrapper .assignment-details .content-details .due-date {
    color: #839096;
    font-size: 13px;
}

.assignment-details-wrapper .show-type-holder { padding: 10px; }
.assignment-details-wrapper .show-type-holder .show-type { display: inline-block; width: 120px; }
.assignment-details-wrapper .student-lists ul .group-name a {
    background-color: #757f93;
    border-radius: 0;
    color: #ffffff;
    padding: 12px;
}

.assignment-details-wrapper .student-lists ul li .show-taker-details {
    background-color: #ffffff;
    border-radius: 0;
}

.assignment-details-wrapper .student-lists ul li.active .show-taker-details {
    background-color: #f3f5f7;
    color: #2a6496;
}

.assignment-manager-wrapper .assignment-manager-default { padding: 0; }
.assignment-manager-default .default-header { border-bottom: 1px solid #dfe4e8; padding: 15px; }
.assignment-manager-default .default-header .assignment-details h3 { margin: 0; padding: 0; }
.assignment-manager-default .assignment-recipients { background-color: #f3f5f7; border-bottom: 1px solid #dfe4e8; padding: 15px; }
.assignment-manager-default .assignment-description { padding: 30px 15px; }

.assignment-manager-wrapper .assignment-submission-wrapper { display: none; padding: 0; }
.assignment-submission-wrapper .manager-header { border-bottom: 1px solid #dfe4e8; padding: 15px; }
.assignment-submission-wrapper .manager-header .taker-details { margin-left: 10px; }
.assignment-submission-wrapper .manager-header .taker-details h4 { margin: 0 0 5px; }
.assignment-submission-wrapper .manager-header .taker-details .not-turned-in-message { color: #839096; }
.assignment-submission-wrapper .manager-header .taker-score { font-size: 16px; }
.assignment-submission-wrapper .manager-header .taker-score .no-score-set { display: none; }
.assignment-submission-wrapper .manager-header .taker-score .no-score-set form { margin: 0; }
.assignment-submission-wrapper .manager-header .taker-score .no-score-set form input {
    display: inline-block;
    margin: 0;
    width: 50px;
}

.assignment-submission-wrapper .manager-header .taker-score .user-total-score { font-size: 32px; }

.assignment-submission-wrapper .manager-header .taker-score .no-score-set form button { margin-left: 10px; }

.assignment-submission-wrapper .no-submission { padding: 80px 0; text-align: center; display: none; }
.assignment-submission-wrapper .assignment-response { padding: 30px 15px; }
</style>
@stop

@section('content')
<div class="message-holder"><span></span></div>
<div class="assignment-manager-wrapper">
    <div class="row">
        <div class="col-md-3">
            <div class="assignment-details-wrapper well">
                <div class="assignment-details page-header">
                    <i class="fa fa-clipboard assignment-sheet-icon"></i>
                    <div class="content-details">
                        <p>{{ $assignment->title }}</p>
                        <span class="due-date">
                            Due {{ date('M d, Y', strtotime($post->assignment_due_date)) }}
                        </span>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="show-type-holder">
                    <span>Showing:</span>
                    <select class="show-type form-control">
                        <option value="all" selected>All</option>
                        <option value="ungraded">Ungraded</option>
                        <option value="graded">Graded</option>
                        <option value="not_turned_in">Not turned in</option>
                    </select>
                </div>
                <div class="student-lists">
                    <ul class="list-holder nav nav-pills nav-stacked">
                        @foreach($takers as $taker)
                        <li class="group-name"><a href="#">{{ $taker->group_name }}</a></li>
                        @if(isset($taker->members))
                        @foreach($taker->members as $member)
                        <li>
                            <a href="#" class="show-taker-details"
                            data-user-id="{{ $member->id }}">
                                {{ $member->firstname.' '.$member->lastname }}
                            </a>
                        </li>
                        @endforeach
                        @endif
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="assignment-manager-default well">
                <div class="default-header">
                    <div class="assignment-details pull-left">
                        <h3>{{ $assignment->title }}</h3>
                        <div class="assignment-manager-controls">
                            <a href="#">Assignment Overview</a>
                            <a href="">All Submissions</a>
                        </div>
                    </div>
                    <div class="assignment-options pull-right">
                        <a href="#">Assignment Options</a>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="assignment-recipients">
                    <span>Assigned to:</span>
                    <?php $groupCount = count($takers); ?>
                    @foreach($takers as $key => $taker)
                    @if($groupCount - 1 === $key)
                    <a href="/groups/{{ $taker->group_id }}">
                        {{ $taker->group_name }}
                    </a>
                    @endif
                    @if($groupCount - 1 !== $key)
                    <a href="/groups/{{ $taker->group_id }}">
                        {{ $taker->group_name }},
                    </a>
                    @endif
                    @endforeach
                </div>
                <div class="assignment-description">{{ $assignment->description }}</div>
            </div>
            <div class="assignment-submission-wrapper well"></div>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="/assets/js/sitefunc/assignmentmanager.js"></script>
@stop