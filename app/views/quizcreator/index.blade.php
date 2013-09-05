@extends('templates.master')

@section('title')
Quiz Creator
@stop

@section('internalCss')
<style type="text/css">
.quiz-first-question-wrapper .quiz-first-question-form .form-group .form-control { display: inline-block; width: 150px; }
</style>
@stop

@section('content')
<div class="quiz-creator-wrapper">
    <div class="row">
        <div class="col-md-9">
            <div class="quiz-creator-header well">

            </div>

            <div class="row">
                <div class="col-md-2">
                    <div class="well">

                    </div>
                </div>

                <div class="col-md-10">
                    <div class="quiz-creator-main-wrapper well">

                        <div class="quiz-creator-welcome-wrapper">
                            <div class="page-header">
                                <h4>Add your first question to start creating a quiz...
                                </h4>
                            </div>
                            <div class="quiz-first-question-wrapper">
                                {{ Form::open(array('url'=>'ajax/quiz-creator', 'class'=>'quiz-first-question-form')) }}
                                    <div class="form-group col-md-5">
                                        <label for="first-question-type">Type</label>
                                        <select name="first-question-type" id="first_question_type"
                                        class="form-control">
                                            <option value="MULTIPLE_CHOICE" selected>Multiple Choice</option>
                                            <option value="TRUE_FALSE">True or False</option>
                                            <option value="SHORT_ANSWER">Short Answer</option>
                                        </select>
                                    </div>
                                    <button type="button" id="submit_first_question" class="btn btn-default">
                                        Add First Question
                                    </button>
                                    <span class="">or</span>
                                    <a href="#">Load First Question</a>
                                {{ Form::close() }}
                            </div>

                            <div class="clearfix"></div>
                        </div>

                        <div class="quiz-creator-proper">

                        </div>

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="well">

            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>

</script>
@stop
