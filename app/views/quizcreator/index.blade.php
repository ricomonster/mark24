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
.item-list-wrapper ul li.has-error a,
.item-list-wrapper ul li.has-error:hover a { color: #b94a48; font-weight: bold; }

.quiz-creator-header { padding: 19px 15px; }
.quiz-creator-header .form-group { display: inline-block; margin: 0; }
.quiz-creator-header .form-group:first-child { width: 475px; }
.quiz-creator-header .form-group label { font-weight: bold; }
.quiz-creator-header .form-group #quiz_time_limit { display: inline-block; width: 50px; }

.quiz-creator-welcome-wrapper .page-header { padding: 0 19px; }

.quiz-first-question-wrapper { margin-bottom: 10px; padding: 0 15px; }
.quiz-first-question-wrapper .form-group { display: inline-block; width: 200px; }
.quiz-first-question-wrapper .form-group .form-control { display: inline-block; width: 150px; }
.quiz-first-question-wrapper .other-options { display: inline-block; }

.quiz-creator-welcome-message-wrapper { padding: 0 19px 19px; }

.quiz-creator-proper { display: none; }
.quiz-creator-proper .question-proper-header { border-bottom: 1px solid #e3e3e3; padding: 19px 15px; }
.quiz-creator-proper .question-proper-header .form-group { display: inline-block; margin: 0 10px 0; }
.quiz-creator-proper .question-proper-header .form-group #question_type { display: inline-block; width: 150px; }
.quiz-creator-proper .question-proper-header .form-group #question_point { display: inline-block; width: 50px; }
.quiz-creator-proper .question-proper-header .form-group .remove-question { display: inline-block; display: none; }

.quiz-creator-proper .question-stream-holder { list-style: none; margin: 0; padding: 0; }
.quiz-creator-proper .question-stream-holder .question-wrapper { display: none; }
.quiz-creator-proper .question-stream-holder .active-question { display: block; }
.quiz-creator-proper .question-stream-holder .question-wrapper textarea { resize: none; }
.quiz-creator-proper .question-stream-holder .question-wrapper .question-prompt-wrapper { border-bottom: 1px solid #e3e3e3; padding: 19px; }

.quiz-creator-proper .question-stream-holder .question-responses-wrapper { padding: 19px; }
.quiz-creator-proper .question-stream-holder .question-responses-wrapper .question-response-title { display: block; font-weight: bold; }

/* Responses */
/* Multiple Choice */
.multiple-choice-response .multiple-choice-response-holder { list-style: none; margin: 0; padding: 0; }
.multiple-choice-response .multiple-choice-response-holder li { margin-bottom: 10px; }
.multiple-choice-response .multiple-choice-response-holder li .option-holder .choice-letter,
.multiple-choice-response .multiple-choice-response-holder li .option-holder .form-control {
    display: inline-block;
}
.multiple-choice-response .multiple-choice-response-holder li .option-holder {
    background-color: #ccc;
    padding: 1px 1px 3px;
}

.multiple-choice-response .multiple-choice-response-holder li .option-holder .choice-letter { font-weight: bold; text-align: center; width: 25px; }
.multiple-choice-response .multiple-choice-response-holder li .form-control { margin-left: 0; width: 94.5% !important; }
.multiple-choice-response .multiple-choice-response-holder li .option-controls a { padding: 2px 10px; }

.multiple-choice-response .multiple-choice-response-holder li.correct-option .option-holder { background-color: #439724; }
.multiple-choice-response .multiple-choice-response-holder li.correct-option .choice-letter { color: #ffffff; }
.multiple-choice-response .multiple-choice-response-holder li.correct-option .option-controls .correct-answer {
    background-color: #439724;
    color: #ffffff;
}

.multiple-choice-response .multiple-choice-response-holder li .option-controls .remove-option { color: #ac2f2f; }

.multiple-choice-response .multiple-choice-response-holder li.option-error .option-holder { border: 1px solid #b94a48; }

/* True or False */
.true-false-response .true-false-option { display: inline-block; width: 100px; }

.top-message-holder { display: none; padding: 10px 15px; text-align: center; }
.about-quiz-holder { border-top: 2px solid #e3e3e3; margin-top: 20px; padding-top: 10px; }
.quiz-total-score { font-size: 14px; }
.quiz-description { margin-top: 10px; }

/* Responsive Shizs
---------------------------------------------------------------*/
@media(max-width: 732px) {
    .quiz-creator-header .form-group:first-child { margin-bottom: 10px; width: 100%; }
}

@media(max-width: 610px) and (min-width: 501px) {
    .multiple-choice-response .multiple-choice-response-holder li .form-control {
        width: 93% !important;
    }
}

@media(max-width: 500px) and (min-width: 480px)  {
    .multiple-choice-response .multiple-choice-response-holder li .form-control {
        width: 92% !important;
    }
}

@media(max-width: 565px) {
    .quiz-first-question-wrapper .form-group { width: 100%; }
    .quiz-first-question-wrapper .form-group:first-child select { width: 100%; }
}

@media(max-width: 550px) {
    .quiz-creator-proper .question-proper-header .form-group { width: 45%; }
    .quiz-creator-proper .question-proper-header .remove-question {
        float: left !important;
        margin-top: 10px;
        width: 100%;
    }
}

@media(max-width: 515px) {
    .quiz-creator-proper .question-proper-header .form-group { width: 40%; }
    .quiz-creator-proper .question-proper-header .form-group:first-child { width: 46%; }
}
</style>
@stop

@section('content')
<div class="quiz-creator-wrapper">
    <div class="row">
        <div class="alert alert-danger top-message-holder"></div>
        <div class="col-md-9">
            <div class="quiz-creator-header well">
                <div class="form-group">
                    <input type="text" name="quiz-title" id="quiz_title" class="form-control"
                    value="Untitled quiz-<?php echo date('Y-m-d-h-i-s'); ?>">
                </div>
                <div class="form-group">
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
                        <button id="add_question" class="btn btn-default"><i class="fa fa-plus-circle"></i></button>
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
                                <div class="form-group">
                                    <label for="first-question-type">Type</label>
                                    <select name="first-question-type" id="first_question_type"
                                    class="form-control">
                                        <option value="MULTIPLE_CHOICE" selected>Multiple Choice</option>
                                        <option value="TRUE_FALSE">True or False</option>
                                        <option value="SHORT_ANSWER">Short Answer</option>
                                    </select>
                                </div>
                                <div class="other-options">
                                    <button type="button" id="submit_first_question"
                                    class="btn btn-default">
                                        Add First Question
                                    </button>
                                    <span class="text-muted">or</span>
                                    <a href="#">Load First Question</a>
                                </div>
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
                                <div class="form-group">
                                    <label for="question-type">Type</label>
                                    <select name="question-type" id="question_type"
                                    class="form-control">
                                        <option value="MULTIPLE_CHOICE" selected>Multiple Choice</option>
                                        <option value="TRUE_FALSE">True or False</option>
                                        <option value="SHORT_ANSWER">Short Answer</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="question-point">Points</label>
                                    <input type="text" name="question-point" id="question_point"
                                    class="form-control">
                                </div>
                                <button class="remove-question btn btn-default pull-right">
                                    Remove Question
                                </button>
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
                <button class="btn btn-primary btn-lg btn-block assign-quiz btn-disabled"
                disabled>Assign Quiz</button>

                <div class="about-quiz-holder">
                    <span class="quiz-total-score pull-right label label-primary"></span>
                    <strong>About this Quiz</strong>
                    <textarea class="form-control quiz-description"></textarea>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="/assets/js/sitefunc/quizcreator.js"></script>
@stop
