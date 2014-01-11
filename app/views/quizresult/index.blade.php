@extends('templates.master')

@section('title')
The Quiz Result
@stop

@section('internalCss')
<style>
.quiz-result .welcome-quiz-sheet-wrapper { margin: 90px auto; padding: 0 19px; width: 600px; }
.quiz-result .welcome-quiz-sheet-wrapper .welcome-contents { border-left: 1px solid #ccc; margin-left: 40px; }
.quiz-result .welcome-quiz-sheet-wrapper .welcome-contents h2 {
    border-bottom: 1px solid #ccc;
    margin: 0;
    padding: 19px 0 5px 20px;
}

.quiz-result .welcome-quiz-sheet-wrapper .welcome-contents .quiz-stats { padding: 10px 0 20px 20px; }
.quiz-result .welcome-quiz-sheet-wrapper .welcome-contents .show-results { margin: 0 0 19px 20px; }
.quiz-result .welcome-quiz-sheet-wrapper .welcome-contents .quiz-finished-message { padding: 10px 0 10px 20px; }

.quiz-result-proper .quiz-result-header .quiz-title {
    display: inline-block;
    font-weight: bold;
    width: 340px;
}

.quiz-result-proper .quiz-result-header .questions-completed {
    display: inline-block;
    text-align: center;
    width: 175px;
}

.quiz-result-proper .quiz-result-header .quiz-timer {
    display: inline-block;
    text-align: center;
    width: 90px;
}

.quiz-items-holder { background-color: #f3f5f7; padding: 10px 0; text-align: center; }
.question-items-holder { margin: 15px 0 0 0; list-style: none; padding: 0; }
.question-items-holder li a { padding: 5px 10px; text-align: left; }
.question-items-holder li.active a { border-radius: 0; }
.question-items-holder li.has-error a,
.question-items-holder li.has-error:hover a { color: #b94a48; font-weight: bold; }

.quiz-sheet { padding: 0; }
.quiz-sheet .quiz-sheet-controls {
    background-color: #f3f5f7;
    border-bottom: 1px solid #e3e3e3;
    padding: 19px 10px;
}

.quiz-sheet .quiz-questions-stream { list-style: none; margin: 0; padding: 0; }
.quiz-sheet .quiz-questions-stream .question-holder { display: none; padding: 19px; }
.quiz-sheet .quiz-questions-stream .show-question { display: block; }
.quiz-sheet .quiz-questions-stream .question-holder .question-text { margin-top: 10px; }
.quiz-sheet .quiz-questions-stream .question-holder .question-responses {
    border-top: 1px solid #e3e3e3;
    margin-top: 20px;
    padding-top: 10px;
}

.question-responses .multiple-choice-response-holder {
    margin: 0;
    padding: 0;
    list-style: none;
}

.question-responses .multiple-choice-response-holder .option-wrapper .option-holder {
    background-color: #ccc;
    height: 60px;
    padding: 1px 1px 3px;
    margin-bottom: 10px;
}

.question-responses .multiple-choice-response-holder .option-wrapper .option-holder .choice-letter {
    display: inline-block;
    font-weight: bold;
    text-align: center;
    width: 25px;
}

.question-responses .multiple-choice-response-holder .option-wrapper .option-holder .choice-text {
    background-color: #ffffff;
    cursor: pointer;
    display: inline-block;
    height: 57px;
    padding: 5px 10px;
    width: 94.3%;
}

.question-responses .multiple-choice-response-holder .choice-answer .option-holder {
    background-color: #439724;
}
.question-responses .multiple-choice-response-holder .choice-answer .option-holder .choice-letter {
    color: #ffffff;
}

.question-responses .multiple-choice-response-holder .wrong-choice .option-holder {
    background-color: #d93f44;
}
.question-responses .multiple-choice-response-holder .wrong-choice .option-holder .choice-letter {
    color: #ffffff;
}

.assigned-wrapper { border-top: 2px solid #dfe4e8; margin-top: 15px; padding-top: 10px; }
.assigned-wrapper .assigned-details { margin-top: 5px; }
.assigned-wrapper .assigned-details p { margin: 0 0 0 10px; }
.instruction-wrapper { border-top: 2px solid #dfe4e8; margin-top: 15px; padding-top: 10px; }
/* Responsive Shizs
---------------------------------------------------------------*/
@media (max-width: 420px) {
    .quiz-result-proper .quiz-result-header .questions-completed { margin-bottom: 10px }
}

@media (max-width: 991px) {
    .quiz-result-proper .quiz-result-header .quiz-title { margin-bottom: 10px; width: 100%; }
}
</style>
@stop

@section('content')

<div class="message-holder">
    <span></span>
</div>

<div class="quiz-result" data-quiz-id="{{ $quiz->quiz_id }}"
data-time-limit="{{ $quiz->time_limit }}">
    <div class="welcome-quiz-sheet-wrapper well"  style="display: none;">
        <div class="welcome-contents">
            <h2>{{ $quiz->title }}</h2>
            <div class="quiz-stats">
                <span class="total-questions">Total questions:
                <?php echo count($questions['list']); ?></span>
                <span class="quiz-result-divider">|</span>
                <span class="time-limit-quiz">Time Limit: 1:00:00</span>
                <span class="quiz-result-divider">|</span>
                <span class="time-limit-quiz">Time Taken: 1:00:00</span>
            </div>
            <div class="quiz-finished-message">
                <span>This quiz is finished.
                You completed 3/<?php echo count($questions['list']); ?>
                questions.</span>
            </div>
            <button class="btn btn-primary btn-large show-results"
            data-quiz-id="{{ $quiz->quiz_id }}">
                View Results
            </button>
        </div>
    </div>

    <div class="quiz-result-proper">
        <div class="row">
            <div class="col-md-9">
                <div class="quiz-result-header well">
                    <input type="text" class="form-control quiz-title" value="{{ $quiz->title }}">
                    <span class="questions-completed">3/<?php echo count($questions['list']); ?>
                    questions completed</span>
                    <span class="quiz-result-label">00:00:00 limit</span>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <div class="well quiz-items-holder"
                            <span>QUESTIONS</span>
                            <ul class="question-items-holder nav nav-stacked nav-pills">
                                @foreach($questions['list'] as $key => $question)
                                <li <?php echo ($key == 0) ? 'class="active"' : null; ?>>
                                    <a href="#" data-question-list-id="{{ $question['question_list_id'] }}"
                                    data-question-id="{{ $question['question']['question_id'] }}"
                                    class="question-item">
                                        {{ $key + 1 }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>

                    <div class="col-md-10">
                        <div class="quiz-sheet well">
                            <div class="quiz-sheet-controls">
                                <span class="question-number-label">Question 1</span>
                                <div class="quiz-sheet-navs pull-right">
                                    <button class="show-previous btn btn-default" disabled>
                                        <i class="fa fa-chevron-left"></i>
                                    </button>
                                    <button class="show-next btn btn-default">
                                        <i class="fa fa-chevron-right"></i>
                                    </button>
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <ul class="quiz-questions-stream">
                                @foreach($questions['list'] as $key => $question)
                                <?php $response = $question['question']['response']; ?>
                                <?php $answer = $question['question']['answer_details']; ?>
                                <?php
                                $answer = Helper::getAnswer(
                                    $takerDetails['quiz_taker_id'],
                                    $question['question']['question_id']);
                                ?>
                                <li class="question-holder <?php echo ($key == 0) ? 'show-question' : null; ?>"
                                data-question-list-id="{{ $question['question_list_id'] }}"
                                data-question-id="{{ $question['question']['question_id'] }}">
                                    @if(empty($answer))
                                    <div class="question-status pull-left label label-warning">
                                        You haven't answered this question
                                    </div>
                                    @endif
                                    @if(!empty($answer) && $answer['is_correct'] === 'TRUE')
                                    <div class="question-status pull-left label label-success">
                                        Your answer is correct
                                    </div>
                                    @endif
                                    @if(!empty($answer) && $answer['is_correct'] === 'FALSE')
                                    <div class="question-status pull-left label label-danger">
                                        Your answer is not correct
                                    </div>
                                    @endif
                                    @if(!empty($answer) && empty($answer['is_correct']))
                                    <div class="question-status pull-left label label-info">
                                        Your answer is not yet graded
                                    </div>
                                    @endif
                                    <div class="question-point pull-right">Question Total: {{ $question['question']['question_point'] }} points</div>
                                    <div class="clearfix"></div>

                                    <div class="question-text">
                                        {{ $question['question']['question'] }}
                                    </div>

                                    <div class="question-responses">
                                        @if($question['question']['question_type'] == 'MULTIPLE_CHOICE')
                                        <ul class="multiple-choice-response-holder">
                                            <?php $alpha = 'A'; ?>
                                            @foreach($response as $key => $choice)
                                            @if(empty($answer) || $answer['is_correct'] === 'TRUE')
                                            <li class="clearfix option-wrapper
                                            <?php
                                            echo ($choice['is_answer'] === 'TRUE') ?
                                            'choice-answer' : null;
                                            ?>">
                                                <div class="option-holder">
                                                    <div class="choice-letter">
                                                        <?php echo ($key == 0) ? 'A' : ++$alpha; ?>
                                                    </div>
                                                    <div class="choice-text">
                                                        {{ $choice['choice_text'] }}
                                                    </div>
                                                </div>
                                            </li>
                                            @endif

                                            @if(!empty($answer) && $answer['is_correct'] === 'FALSE')
                                            <li class="clearfix option-wrapper
                                            <?php
                                            echo ($choice['is_answer'] === 'TRUE') ? 'choice-answer' : null;
                                            ?>
                                            <?php
                                            echo ($choice['multiple_choice_id'] === $answer['multiple_choice_answer']) ?
                                            'wrong-choice' : null;
                                            ?>">
                                                <div class="option-holder">
                                                    <div class="choice-letter">
                                                        <?php echo ($key == 0) ? 'A' : ++$alpha; ?>
                                                    </div>
                                                    <div class="choice-text">
                                                        {{ $choice['choice_text'] }}
                                                    </div>
                                                </div>
                                            </li>
                                            @endif
                                            @endforeach
                                        </ul>
                                        @elseif($question['question']['question_type'] == 'TRUE_FALSE')
                                        <div class="response-true-false"
                                        data-question-id="{{ $question['question']['question_id'] }}">
                                            @if(empty($answer) || $answer['is_correct'] === 'TRUE')
                                            <button class="btn true-false-answer
                                            <?php echo ($response['answer'] === 'TRUE') ?
                                            'btn-success' : 'btn-default '; ?>">
                                                TRUE
                                            </button>

                                            <button class="btn true-false-answer
                                            <?php echo ($response['answer'] === 'FALSE') ?
                                            'btn-success' : 'btn-default '; ?>">
                                                FALSE
                                            </button>
                                            @endif
                                            @if(!empty($answer))
                                            @if($answer['is_correct'] === 'FALSE')
                                            @if($answer['true_false_answer'] === 'TRUE')
                                            <button class="btn true-false-answer btn-danger">TRUE</button>
                                            @else
                                            <button class="btn true-false-answer btn-success">TRUE</button>
                                            @endif

                                            @if($answer['true_false_answer'] === 'FALSE')
                                            <button class="btn true-false-answer btn-danger">FALSE</button>
                                            @else
                                            <button class="btn true-false-answer btn-success">FALSE</button>
                                            @endif
                                            @endif

                                            @endif
                                        </div>
                                        @elseif($question['question']['question_type'] == 'SHORT_ANSWER')
                                        <?php
                                        $shortAnswerText = (!empty($answer) && !empty($answer->short_answer_text)) ?
                                            $answer->short_answer_text : null;
                                        ?>
                                        <div class="response-short-answer">
                                            <textarea class="form-control short-answer-text"
                                            data-question-id="{{ $question['question']['question_id'] }}">{{ $shortAnswerText }}</textarea>
                                        </div>
                                        @endif
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="quiz-result-details well">
                    <div class="quiz-results"></div>

                    <div class="assigned-wrapper">
                        <strong>Assigned By</strong>
                        <div class="assigned-details">
                            {{ Helper::avatar(50, "small", "pull-left", $assigned->id) }}
                            <div class="pull-left">
                                <p>{{ $assigned->name }}</p>
                                <p>Teacher</p>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="instruction-wrapper">
                        <strong>Instructions</strong>
                        <p>
                            Answer each question to the left. When you have answered
                            all of the questions, click the "Submit Quiz" button above.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('js')
<script type="text/javascript" src="/assets/js/sitefunc/quizresult.js"></script>
<script>
// // prototype counter
// var counter = 0;
// var interval = setInterval(function() {
//     counter++;
//     // Display 'counter' wherever you want to display it.
//     console.log(counter);
//     if (counter == 5) {
//         // Display a login box
//         // alert('TIME!');
//         clearInterval(interval);
//     }
// }, 1000);
</script>
@stop
