@foreach($questions['list'] as $key => $question)
<?php
$answer = Helper::getAnswer(
    $quizTakerId,
    $question['question']['question_id']);
?>
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
            <li class="clearfix option-wrapper
            <?php
            echo (!empty($answer) && $answer->multiple_choice_answer == $r['multiple_choice_id']) ?
            ' choice-answer' : null; ?>
            ?>">
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
            <button class="btn btn-default true-false-answer
            <?php
            echo (!empty($answer) && $answer->true_false_answer == 'TRUE') ?
            ' btn-success' : null; ?>
            ?>" data-question-id="{{ $question['question']['question_id'] }}"
            data-answer="TRUE">
                TRUE
            </button>
            <button class="btn btn-default true-false-answer
            <?php
            echo (!empty($answer) && $answer->true_false_answer == 'FALSE') ?
            ' btn-success' : null; ?>
            ?>" data-question-id="{{ $question['question']['question_id'] }}"
            data-answer="FALSE">
                FALSE
            </button>
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
        @elseif($question['question']['question_type'] == 'IDENTIFICATION')
        <?php
        $indentificationAnswer = (!empty($answer) && !empty($answer->identification_answer)) ?
            $answer->identification_answer : null;
        ?>
        <div class="response-identification">
            <input type="text" class="form-control identification-answer"
            data-question-id="{{ $question['question']['question_id'] }}"
            value="{{ $indentificationAnswer }}">
        </div>
        @endif
    </div>
</li>
@endforeach
