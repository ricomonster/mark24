<div class="manager-header">
    {{ Helper::avatar(42, "small", "img-rounded pull-left", $userDetails->id) }}

    <div class="taker-details pull-left">
        <h4>{{ $userDetails->name }}</h4>
        @if(empty($questions))
        <div class="not-turned-in-message">Not turned in</div>
        @endif
        @if(!empty($questions))
        <div class="quiz-taken-details text-muted">
            <span>Time Taken: {{ $timeTaken }}</span>
            <span>|</span>
            <span>Turned in {{ date('M d, Y h:i A') }}</span>
        </div>
        <div class="taken-controls">
            <span>Graded</span>
            <span>|</span>
            <a href="#">Delete</a>
        </div>
        @endif
    </div>

    <div class="taker-stats pull-right">
        <h2 class="total-point-holder">
            <span class="user-points">{{ (empty($questions)) ? '0' : $takerDetails->score; }}</span>
            /
            {{ $quiz->total_score }}
        </h2>
        <span class="text-muted">Total Points</span>
    </div>
    <div class="clearfix"></div>
</div>
@if(empty($questions))
<div class="user-no-answer-wrapper">{{ $userDetails->name }} has not submitted this quiz yet.</div>
@endif
@if(!empty($questions))
<ul class="nav nav-tabs quiz-item-pagination">
    @foreach($questions['list'] as $key => $question)
    <?php
    $answer = $question['question']['answer_details'];
    if(empty($answer)) {
        $class = 'no-answer';
    } else if(!empty($answer) && $answer['is_correct'] === 'TRUE') {
        $class = 'true-answer';
    } else if(!empty($answer) && $answer['is_correct'] === 'FALSE'){
        $class = 'false-answer';
    } else if(!empty($answer) && empty($answer['is_correct'])) {
        $class = 'no-grade';
    }
    ?>
    <li class="{{ ($key == 0) ? 'active' : null }} {{ $class }}">
        <a href="#{{ $key + 1 }}" data-toggle="tab">
            {{ $key + 1 }}
        </a>
    </li>
    @endforeach
</ul>
<div class="tab-content question-item-holder">
    @foreach($questions['list'] as $key => $question)
    <?php $response = $question['question']['response']; ?>
    <?php $answer = $question['question']['answer_details']; ?>
    <div class="tab-pane <?php echo ($key == 0) ? 'active' : null; ?>"
    id="{{ $key + 1 }}" data-question-id="{{ $question['question']['question_id'] }}">
        @if(empty($answer))
        <div class="question-status pull-left label label-warning">
            Question not answered
        </div>
        @endif
        @if(!empty($answer) && $answer['is_correct'] === 'TRUE')
        <div class="question-status pull-left label label-success">
            Answer is correct
        </div>
        @endif
        @if(!empty($answer) && $answer['is_correct'] === 'FALSE')
        <div class="question-status pull-left label label-danger">
            Answer is not correct
        </div>
        @endif
        @if(!empty($answer) && empty($answer['is_correct']))
        <div class="question-status pull-left label label-info">
            Answer is not yet graded
        </div>
        @endif
        <div class="question-point pull-right">
            Question Total:
            <?php
            echo ($question['question']['question_point'] == 1) ?
            $question['question']['question_point'] . ' point' :
            $question['question']['question_point'] . ' points';
            ?>
        </div>
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
            @endif
            @if($question['question']['question_type'] == 'TRUE_FALSE')
            <div class="response-true-false">
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
            @endif
            @if($question['question']['question_type'] == 'SHORT_ANSWER')
            <div class="response-short-answer">
                <?php echo nl2br(htmlentities(($answer['short_answer_text']))); ?>
            </div>
            @endif
            @if($question['question']['question_type'] == 'IDENTIFICATION')
            <div class="response-identification">
                <div class="student-identification-response
                {{ ($answer['is_correct'] === 'FALSE') ? 'wrong' : null }}">
                    {{ (empty($answer['identification_answer']))
                    ? 'User did not answer the question' :$answer['identification_answer'] }}
                </div>
                @if($answer['is_correct'] === 'FALSE')
                <strong>Answer:</strong>
                <div class="identification-answer">
                    {{ $response['answer'] }}
                </div>
                @endif
            </div>
            @endif
        </div>
        @if(!empty($answer) && empty($answer['is_correct']))
        <div class="answer-ungraded">
            <button class="btn btn-default answer-is-correct set-answer-state"
            data-total-point="{{ $question['question']['question_point'] }}"
            data-answer="correct" data-question-id="{{ $question['question']['question_id'] }}"
            data-answer-id="{{ $answer['quiz_answer_id'] }}">Correct</button>

            <button class="btn btn-default answer-is-incorrect set-answer-state"
            data-total-point="{{ $question['question']['question_point'] }}"
            data-answer="incorrect" data-question-id="{{ $question['question']['question_id'] }}"
            data-answer-id="{{ $answer['quiz_answer_id'] }}">Incorrect</button>

            <div class="partial-credit-wrapper">
                <span class="text-muted">Partial Credit</span>
                <input type="text" name="partial-credit"
                class="partial-credit form-control"
                data-total-point="{{ $question['question']['question_point'] }}"
                data-answer-id="{{ $answer['quiz_answer_id'] }}"
                data-question-id="{{ $question['question']['question_id'] }}">

                <span class="text-muted">/</span>
                <span class="total-question-point text-muted">
                    {{ $question['question']['question_point'] }}
                </span>
            </div>
        </div>
        @endif
    </div>
    @endforeach
</div>
@endif
