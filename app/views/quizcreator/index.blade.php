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

.multiple-choice-response .multiple-choice-response-holder li.option-error .option-holder { border: 1px solid red; }

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
                        <ul class="nav nav-stacked nav-pills">
                            <li class="active"><a href="">1</a></li>
                        </ul>
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

                            <ul class="question-stream-holder">
                                <li class="question-wrapper">
                                    <div class="question-prompt-wrapper">
                                        <div class="form-group">
                                            <label class="question-prompt">Question Prompt:</label>
                                            <textarea name="question-prompt" id="question_prompt"
                                            class="form-control"></textarea>
                                        </div>
                                    </div>

                                    <div class="question-responses-wrapper">
                                        <span class="question-response-title">Responses:</span>

                                        <div class="multiple-choice-response" style="display: none;">
                                            <ul class="multiple-choice-response-holder">
                                                <li class="clearfix correct-option">
                                                    <div class="option-holder">
                                                        <div class="choice-letter">A</div>
                                                        <textarea class="form-control multipe-choice-option"></textarea>
                                                    </div>

                                                    <div class="option-controls">
                                                        <a href="" class="pull-right">Correct Answer</a>
                                                    </div>
                                                </li>
                                                <li class="clearfix">
                                                    <div class="option-holder">
                                                        <div class="choice-letter">B</div>
                                                        <textarea class="form-control multipe-choice-option"></textarea>
                                                    </div>
                                                    <div class="option-controls">
                                                        <a href="" class="pull-right">Set as Correct Answer</a>
                                                    </div>
                                                </li>
                                            </ul>
                                            <div class="clearfix"></div>
                                            <button class="btn btn-default">Add Response</button>
                                            <div class="clearfix"></div>
                                        </div>

                                        <div class="true-false-response">
                                            <span class="label label-success">Correct Answer</span>
                                            <select class="true-false-option form-control">
                                                <option value="TRUE" selected>True</option>
                                                <option value="FALSE">False</option>
                                            </select>
                                        </div>

                                        <div class="short-answer-response">

                                        </div>
                                    </div>
                                </li>
                            </ul>
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
// Quiz Creator version 4.x
var QuizCreator = {
    init : function(config) {
        // this acts as the constructor which gets
        // all of the elements/data that the init gets
        this.config = config;
        this.bindEvents();
    },

    bindEvents : function() {
        // all of event triggers are here
        // it will fire the certain function that the 
        // event has on it
        $(document)
            .on('click', this.config.buttonSubmitFirstQuestion.selector, this.createNewQuiz);
    },

    // creates a new quiz and creates the first question
    createNewQuiz : function() {
        var self            = QuizCreator;
        var quizTimeLimit   = 0;

        self.config.messageHolder.show().find('span').text('Saving...');
        // check if quiz time limit is empty.
        // if empty, set the time limit to 1 hour or 60 minutes
        if(self.config.inputQuizTimeLimit.val().length == 0) {
            // set time limit to 60 minutes
            quizTimeLimit = 60;
        } else {
            quizTimeLimit = self.config.inputQuizTimeLimit.val();
        }

        $.ajax({
            type    : 'post',
            url     : '/ajax/quiz-creator/create-new-quiz',
            data    : {
                quiz_title      : self.config.inputQuizTitle.val(),
                quiz_time_limit : quizTimeLimit,
                question_type   : self.config.selectFirstQuestionType.val()
            },
            async   : false
        }).done(function() {
            // hide the welcome wrapper
            self.config.welcomeMessageWrapper.hide();
            // show the quiz proper
            self.config.quizCreatorProper.show();
            // pop out the quiz item holder
            self.config.itemListWrapper.show();
            // set the global details
            // load the first question wrapper

            // hide message holder
            self.config.messageHolder.hide();
        });
    }
}

QuizCreator.init({
    // get all the elements we need!
    // global
    // div/span/p/strong/
    messageHolder : $('.message-holder'),

    welcomeMessageWrapper : $('.quiz-creator-welcome-wrapper'),
    itemListWrapper : $('.item-list-wrapper'),
    quizCreatorProper : $('.quiz-creator-proper'),
    // buttons
    buttonSubmitFirstQuestion : $('#submit_first_question'),
    // form elements
    inputQuizTitle : $('#quiz_title'),
    inputQuizTimeLimit : $('#quiz_time_limit'),

    selectFirstQuestionType : $('#first_question_type')
})
</script>
@stop
