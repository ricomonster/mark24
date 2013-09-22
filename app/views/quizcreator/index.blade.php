@extends('templates.master')

@section('title')
Quiz Creator
@stop

@section('internalCss')
<style type="text/css">
.quiz-creator-main-wrapper { padding: 0; }

.item-list-wrapper { padding: 19px 0; text-align: center; }
.item-list-wrapper ul { margin: 10px 0 !important; }
.item-list-wrapper ul li a { padding: 5px 10px; }
.item-list-wrapper ul li.active a { border-radius: 0; }

.quiz-creator-header { padding: 19px 0; }
.quiz-creator-header .form-group { margin: 0; }
.quiz-creator-header .form-group label { font-weight: bold; }
.quiz-creator-header .form-group #quiz_time_limit { display: inline-block; width: 50px; }

.quiz-creator-welcome-wrapper .page-header { padding: 0 19px; }

.quiz-first-question-wrapper { margin-bottom: 20px; }
.quiz-first-question-wrapper .form-group .form-control { display: inline-block; width: 150px; }

.quiz-creator-welcome-message-wrapper { padding: 0 19px 19px; }

.quiz-creator-proper { display: none; }
.quiz-creator-proper .question-proper-header { border-bottom: 1px solid #000; padding: 19px 0; }
.quiz-creator-proper .question-proper-header .form-group { margin: 0; }
.quiz-creator-proper .question-proper-header .form-group #question_type { display: inline-block; width: 150px; }
.quiz-creator-proper .question-proper-header .form-group #question_point { display: inline-block; width: 50px; }

.quiz-creator-proper .question-stream-holder { list-style: none; margin: 0; padding: 0; }
.quiz-creator-proper .question-stream-holder .question-wrapper { display: none; }
.quiz-creator-proper .question-stream-holder .active-question { display: block; }
.quiz-creator-proper .question-stream-holder .question-wrapper textarea { resize: none; }
.quiz-creator-proper .question-stream-holder .question-wrapper .question-prompt-wrapper { border-bottom: 1px solid #000; padding: 19px; }

.quiz-creator-proper .question-stream-holder .question-responses-wrapper { padding: 19px; }
.quiz-creator-proper .question-stream-holder .question-responses-wrapper .question-response-title { display: block; font-weight: bold; }

/* Responses */
/* Multiple Choice */
.multiple-choice-response .multiple-choice-response-holder { list-style: none; margin: 0; padding: 0; }
.multiple-choice-response .multiple-choice-response-holder li { margin-bottom: 10px; }
.multiple-choice-response .multiple-choice-response-holder li .option-holder .choice-letter,
.multiple-choice-response .multiple-choice-response-holder li .option-holder .form-control { display: inline-block; }
.multiple-choice-response .multiple-choice-response-holder li .option-holder { background-color: #ccc; padding: 1px 1px 3px; }
.multiple-choice-response .multiple-choice-response-holder li .option-holder .choice-letter { font-weight: bold; text-align: center; width: 25px; }
.multiple-choice-response .multiple-choice-response-holder li .form-control { margin-left: 0; width: 94.5% !important; }
.multiple-choice-response .multiple-choice-response-holder li .option-controls a { padding: 2px 10px; }

.multiple-choice-response .multiple-choice-response-holder li.correct-option .option-holder { background-color: #439724; }
.multiple-choice-response .multiple-choice-response-holder li.correct-option .choice-letter { color: #ffffff; }
.multiple-choice-response .multiple-choice-response-holder li.correct-option .option-controls a { background-color: #439724; color: #ffffff; }

.multiple-choice-response .multiple-choice-response-holder li.option-error .option-holder { border: 1px solid #b94a48; }

/* True or False */
.true-false-response .true-false-option { display: inline-block; width: 100px; }
</style>
@stop

@section('content')

<div class="message-holder">
    <span></span>
</div>

<div class="quiz-creator-wrapper">
    <div class="row">
        <div class="col-md-9">
            <div class="quiz-creator-header well">
                <div class="form-group col-md-7">
                    <input type="text" name="quiz-title" id="quiz_title" class="form-control"
                    value="Untitled quiz-<?php echo date('Y-m-d-h-i-s'); ?>">
                </div>
                <div class="form-group col-md-4">
                    <label class="quiz-time-limit">Time Limit</label>
                    <input text="text" name="quiz-time-limit" id="quiz_time_limit"
                    class="form-control">
                    <span>Minutes</span>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="row">
                <div class="col-md-2">
                    <div class="item-list-wrapper well" style="display: none;">
                        <span class="">QUESTIONS</span>
                        <ul class="item-list-holder nav nav-stacked nav-pills"></ul>
                        <button id="add_question" class="btn btn-default"><i class="icon-plus"></i></button>
                    </div>
                </div>

                <div class="col-md-10">
                    <div class="quiz-creator-main-wrapper well">

                        <div class="quiz-creator-welcome-wrapper">
                            <div class="page-header">
                                <h4>
                                    Add your first question to start creating a quiz...
                                </h4>
                            </div>

                            <div class="quiz-first-question-wrapper">
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
                            </div>

                            <div class="quiz-creator-welcome-message-wrapper">
                                <strong>Quiz Help</strong>
                                
                                <p>
                                    Changes made to the quiz will automatically save.
                                    You can assign or edit this quiz at a later time by
                                    loading it from the Post Box on the Home page.
                                </p>
                                <p>
                                    Learn more about quizzes in the Help Center.
                                </p>
                            </div>

                            <div class="clearfix"></div>
                        </div>

                        <div class="quiz-creator-proper">
                            <div class="question-proper-header">
                                <div class="form-group col-md-5">
                                    <label for="question-type">Type</label>
                                    <select name="question-type" id="question_type"
                                    class="form-control">
                                        <option value="MULTIPLE_CHOICE" selected>Multiple Choice</option>
                                        <option value="TRUE_FALSE">True or False</option>
                                        <option value="SHORT_ANSWER">Short Answer</option>
                                    </select>
                                </div>
                                <div class="form-group col-md-3">
                                    <label for="question-point">Points</label>
                                    <input type="text" name="question-point" id="question_point"
                                    class="form-control">
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <!-- question stream -->
                            <ul class="question-stream-holder"></ul>

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
<script src="/assets/js/sitefunc/quizcreator.js"></script>
@stop
