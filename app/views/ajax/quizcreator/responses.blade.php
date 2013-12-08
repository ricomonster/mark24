@if($question->question_type == 'MULTIPLE_CHOICE')
<div class="multiple-choice-response">
    <ul class="multiple-choice-response-holder">
        <?php $alpha = 'A'; ?>
        @foreach($response as $key => $r)
        <li class="clearfix option-wrapper <?php if($r->is_answer == 'TRUE') { ?>correct-option<?php  } ?>">
            <div class="option-holder">
                <div class="choice-letter"><?php echo ($key == 0) ? 'A' : ++$alpha; ?></div>
                <textarea class="form-control multiple-choice-option"
                data-multiple-choice-id="{{ $r->multiple_choice_id }}"
                data-question-id="{{ $question->question_id }}">{{ $r->choice_text }}</textarea>
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
        @endforeach
    </ul>
    <div class="clearfix"></div>
    <button class="btn btn-default add-response"
    data-question-id="{{ $question->question_id }}">Add Response</button>
    <div class="clearfix"></div>
</div>

@elseif($question->question_type == 'TRUE_FALSE')

<div class="true-false-response"
    <span class="label label-success">Correct Answer</span>
    <select class="true-false-option form-control"
    data-true-false-id="{{ $response->true_false_id }}">
        <option value="TRUE"
        <?php echo ($response->answer == 'TRUE') ? 'selected' : null; ?>>True</option>
        <option value="FALSE"
        <?php echo ($response->answer == 'FALSE') ? 'selected' : null; ?>>False</option>
    </select>
</div>

@endif
