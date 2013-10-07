<li class="clearfix option-wrapper">
    <div class="option-holder">
        <div class="choice-letter"></div>
        <textarea class="form-control multiple-choice-option"
        data-multiple-choice-id="{{ $r->multiple_choice_id }}"
        data-question-id="{{ $question_id }}">{{ $r->choice_text }}</textarea>
    </div>

    <div class="option-controls">
        @if($r->is_answer == 'TRUE')
        <a href="#" class="pull-right correct-answer"
        data-multiple-choice-id="{{ $r->multiple_choice_id }}">Correct Answer</a>
        @else
        <a href="#" class="pull-right set-as-correct-answer"
        data-multiple-choice-id="{{ $r->multiple_choice_id }}">Set as Correct Answer</a>
        @endif
    </div>
</li>
