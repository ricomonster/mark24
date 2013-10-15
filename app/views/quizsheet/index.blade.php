@extends('templates.master')

@section('title')
The Quiz Sheet
@stop

@section('internalCss')
<style>
.the-quiz-sheet .welcome-quiz-sheet-wrapper { margin: 90px auto; padding: 0 19px; width: 600px; }
.the-quiz-sheet .welcome-quiz-sheet-wrapper .welcome-contents { border-left: 1px solid #ccc; margin-left: 40px; }
.the-quiz-sheet .welcome-quiz-sheet-wrapper .welcome-contents h2 {
    border-bottom: 1px solid #ccc;
    margin: 0;
    padding: 19px 0 5px 20px;
}

.the-quiz-sheet .welcome-quiz-sheet-wrapper .welcome-contents .quiz-stats { padding: 10px 0 20px 20px; }
.the-quiz-sheet .welcome-quiz-sheet-wrapper .welcome-contents .start-quiz { margin: 0 0 19px 20px; }

.the-quiz-sheet-proper .the-quiz-sheet-header .quiz-title {
    display: inline-block;
    font-weight: bold;
    width: 340px;
}

.the-quiz-sheet-proper .the-quiz-sheet-header .questions-completed {
    display: inline-block;
    text-align: center;
    width: 70px;
}

.the-quiz-sheet-proper .the-quiz-sheet-header .quiz-timer {
    display: inline-block;
    text-align: center;
    width: 80px;
}

.question-items-holder { margin: 15px 0 0 0; list-style: none; padding: 0; }
.question-items-holder li a { padding: 5px 10px; }
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
</style>
@stop

@section('content')

<div class="message-holder">
    <span></span>
</div>

<div class="the-quiz-sheet">
    <div class="welcome-quiz-sheet-wrapper well">
        <div class="welcome-contents">
            <h2>{{ $quiz->title }}</h2>
            <div class="quiz-stats">
                <span class="total-questions">Total questions: 1</span>
                <span class="the-quiz-sheet-divider">|</span>
                <span class="time-limit-quiz">Time Limit: 1:00:00</span>
            </div>
            <button class="btn btn-primary btn-large start-quiz"
            data-quiz-id="{{ $quiz->quiz_id }}">
                Start Quiz
            </button>
        </div>
    </div>

    <div class="the-quiz-sheet-proper" style="display: none;">
        <div class="row">
            <div class="col-md-9">
                <div class="the-quiz-sheet-header well">
                    <input type="text" class="form-control quiz-title" value="{{ $quiz->title }}">
                    <input type="text" class="form-control questions-completed" value="0/1" readonly>
                    <span class="the-quiz-sheet-label">questions completed</span>
                    <input type="text" class="form-control quiz-timer" value="1:00:00" readonly>
                    <span class="the-quiz-sheet-label">left</span>
                </div>

                <div class="row">
                    <div class="col-md-2">
                        <span class="">QUESTIONS</span>
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
                    <div class="col-md-10">
                        <div class="quiz-sheet well">
                            <div class="quiz-sheet-controls">
                                <span class="question-number-label">Question 1</span>
                                <div class="quiz-sheet-navs pull-right">
                                    <button class="show-previous btn btn-default" disabled>
                                        <i class="icon-chevron-left"></i>
                                    </button>
                                    <button class="show-next btn btn-default">
                                        <i class="icon-chevron-right"></i>
                                    </button>
                                </div>
                                <div class="clearfix"></div>
                            </div>

                            <ul class="quiz-questions-stream">
                                @foreach($questions['list'] as $key => $question)
                                <li class="question-holder <?php echo ($key == 0) ? 'show-question' : null; ?>"
                                data-question-list-id="{{ $question['question_list_id'] }}"
                                data-question-id="{{ $question['question']['question_id'] }}">
                                    <div class="question-point pull-right">Question Total: {{ $question['question']['question_point'] }} points</div>
                                    <div class="clearfix"></div>

                                    <div class="question-text">
                                        {{ $question['question']['question'] }}
                                    </div>

                                    <div class="question-responses">
                                        @if($question['question']['question_type'] == 'MULTIPLE_CHOICE')
                                        <ul class="multiple-choice-response-holder">
                                            <?php $alpha = 'A'; ?>
                                            @foreach($question['question']['response'] as $key => $r)
                                            <li class="clearfix option-wrapper">
                                                <div class="option-holder">
                                                    <div class="choice-letter">
                                                        <?php echo ($key == 0) ? 'A' : ++$alpha; ?>
                                                    </div>
                                                    <div class="choice-text" data-choice-id="{{ $r['multiple_choice_id'] }}"
                                                    data-question-id="{{ $question['question']['question_id'] }}">
                                                        {{ $r['choice_text'] }}
                                                    </div>
                                                </div>
                                            </li>
                                            @endforeach
                                        </ul>
                                        @elseif($question['question']['question_type'] == 'TRUE_FALSE')
                                        <div class="response-true-false"
                                        data-question-id="{{ $question['question']['question_id'] }}">
                                            <button class="btn btn-default true-false-answer"
                                            data-question-id="{{ $question['question']['question_id'] }}" data-answer="TRUE">
                                                TRUE
                                            </button>
                                            <button class="btn btn-default true-false-answer"
                                            data-question-id="{{ $question['question']['question_id'] }}" data-answer="FALSE">
                                                FALSE
                                            </button>
                                        </div>
                                        @elseif($question['question']['question_type'] == 'SHORT_ANSWER')
                                        <div class="response-short-answer">
                                            <textarea class="form-control"
                                            data-question-id="{{ $question['question']['question_id'] }}"></textarea>
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
                <div class="the-quiz-sheet-details well">

                </div>
            </div>
        </div>
    </div>
</div>

@stop

@section('js')
<script type="text/javascript" src="/assets/js/sitefunc/thequizsheet.js"></script>
@stop
