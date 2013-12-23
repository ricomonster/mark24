@extends('templates.master')

@section('title')
Hello
@stop

@section('internalCss')
<style type="text/css">
.assignment-sheet-wrapper .assignment-header { padding: 0; }
.assignment-header .assignment-title { margin: 0; padding: 15px; }
.assignment-header .assignment-title h3 { margin: 0; padding: 0; }
.assignment-header .assignment-description { padding: 15px; }

.assignment-sheet-wrapper .assignment-sheet-form-wrapper { padding: 0; }
.assignment-sheet-form-wrapper .assignment-user-header { margin: 0; padding: 15px; }
.assignment-sheet-form-wrapper .form-group { padding: 15px 15px 0; }
.assignment-sheet-form-wrapper .form-group textarea { min-height: 100px; resize: none; }
.assignment-sheet-form-wrapper .button-control { padding: 0 15px 15px; }
</style>
@stop

@section('content')
<div class="assignment-sheet-wrapper">
    <div class="row">
        <div class="col-md-3"></div>
        <div class="col-md-9">
            <div class="assignment-header well">
                <div class="assignment-title page-header">
                    <h3>{{ $assignment->title }}</h3>
                </div>
                <div class="assignment-description">{{ $assignment->description }}</div>
            </div>
            <div class="assignment-sheet-form-wrapper well">
                {{ Form::open(array('class' => 'assignment-sheet-form')) }}
                    <div class="assignment-user-header page-header">
                        <div class="user-details">
                            {{ Helper::avatar(42, "normal", "img-rounded pull-left", Auth::user()->id) }}
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <div class="form-group">
                        <textarea class="form-control" name="assignment-response"
                        placeholder="Type your responses here..."></textarea>
                    </div>
                    <div class="button-control">
                        <button type="submit" class="btn btn-primary pull-right">
                            Turn in Assignment
                        </button>
                        <div class="clearfix"></div>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>

</script>
@stop
