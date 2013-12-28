@extends('templates.master')

@section('title')
Assignment Sheet
@stop

@section('internalCss')
<style type="text/css">
.assignment-sheet-wrapper .assignment-details-wrapper { padding: 0; }
.assignment-details-wrapper .assignment-details { margin: 0; padding: 15px; }
.assignment-details-wrapper .assignment-details .assignment-sheet-icon { color: #8c94a7; float: left; font-size: 24px; }
.assignment-details-wrapper .assignment-details .content-details { float: left; margin-left: 10px; }
.assignment-details-wrapper .assignment-details .content-details p { font-size: 16px; margin: 0; padding: 0; }
.assignment-details-wrapper .assignment-details .content-details .due-date {
    color: #839096;
    font-size: 13px;
}
.assignment-details-wrapper .assignment-status { padding: 15px; }

.assignment-sheet-wrapper .assignment-header { padding: 0; }
.assignment-header .assignment-title { margin: 0; padding: 15px; }
.assignment-header .assignment-title h3 { margin: 0; padding: 0; }
.assignment-header .assignment-description { padding: 15px; }

.assignment-sheet-wrapper .assignment-sheet-form-wrapper { padding: 0; }
.assignment-sheet-form-wrapper .assignment-user-header .user-details-wrapper .user-details { margin-left: 50px; }
.assignment-user-header .user-details-wrapper .user-details .user-name { margin: 0; }
.assignment-sheet-form-wrapper .assignment-user-header { margin: 0; padding: 15px; }
.assignment-sheet-form-wrapper .form-group { padding: 15px 15px 0; }
.assignment-sheet-form-wrapper .form-group textarea { min-height: 100px; resize: none; }
.assignment-sheet-form-wrapper .button-control { padding: 0 15px 15px; }

.assignment-sheet-form-wrapper .assignment-response-holder { display: none; min-height: 100px; padding: 15px; }
</style>
@stop

@section('content')
<div class="message-holder"><span></span></div>
<div class="assignment-sheet-wrapper">
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
                <div class="assignment-status">
                    @if(empty($userAssignment))
                    <span>Not turned in</span>
                    @endif
                    @if(!empty($userAssignment))
                    <span>{{ ucfirst(strtolower($userAssignment->status)) }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="assignment-header well">
                <div class="assignment-title page-header">
                    <h3>{{ $assignment->title }}</h3>
                </div>
                <div class="assignment-description">{{ $assignment->description }}</div>
            </div>
            <div class="assignment-sheet-form-wrapper well">
                <div class="assignment-user-header page-header">
                    <div class="user-details-wrapper">
                        {{ Helper::avatar(42, "normal", "img-rounded pull-left", Auth::user()->id) }}
                        <div class="user-details">
                            <h4 class="user-name">{{ Auth::user()->name }}</h4>
                            <div class="user-status text-muted">
                                @if(empty($userAssignment))
                                Not turned in
                                @endif
                                @if(!empty($userAssignment))
                                Submitted on {{ date('F d, Y h:i A', strtotime($userAssignment->created_at)) }}
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </div>
                @if(empty($userAssignment))
                {{ Form::open(array('class' => 'assignment-sheet-form')) }}
                    <div class="form-group">
                        <textarea class="form-control assignment-response" name="assignment-response"
                        placeholder="Type your responses here..."></textarea>
                    </div>
                    <div class="button-control">
                        <input type="hidden" name="assignment-id"
                        value="{{ $assignment->assignment_id }}">
                        <button type="submit" class="btn btn-primary pull-right submit-response">
                            Turn in Assignment
                        </button>
                        <div class="clearfix"></div>
                    </div>
                {{ Form::close() }}
                @endif
                <div class="assignment-response-holder"
                <?php echo (empty($userAssignment)) ? null : ' style="display: block"'; ?>>
                    @if(!empty($userAssignment))
                    {{ $userAssignment->response }}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script type="text/javascript" src="/assets/js/sitefunc/assignmentsheet.js"></script>
@stop
