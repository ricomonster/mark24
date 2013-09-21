<li class="question-wrapper active-question" data-question-list-id="{{ $question->question_list_id }}"
data-question-id="{{ $question->question_id }}">
    <div class="question-prompt-wrapper">
        <div class="form-group">
            <label class="question-prompt">Question Prompt:</label>
            <textarea name="question-prompt" id="question_prompt"
            class="form-control" data-question-id="{{ $question->question_id }}"></textarea>
        </div>
    </div>

    <div class="question-responses-wrapper">
        <span class="question-response-title">Responses:</span>

        <div class="responses-wrapper">
            @if($question->question_type == 'MULTIPLE_CHOICE')
            <div class="multiple-choice-response">
                <ul class="multiple-choice-response-holder">
                    <?php $alpha = 'A'; ?>
                    @foreach($response as $key => $r)
                    <li class="clearfix <?php if($r->is_answer == 'TRUE') { ?>correct-option<?php  } ?>">
                        <div class="option-holder">
                            <div class="choice-letter"><?php echo ($key == 0) ? 'A' : ++$alpha; ?></div>
                            <textarea class="form-control multipe-choice-option"></textarea>
                        </div>

                        <div class="option-controls">
                            @if($r->is_answer == 'TRUE')
                            <a href="#" class="pull-right correct-answer">Correct Answer</a>
                            @else
                            <a href="#" class="pull-right set-as-correct-answer">Set as Correct Answer</a>
                            @endif
                        </div>
                    </li>
                    @endforeach
                </ul>
                <div class="clearfix"></div>
                <button class="btn btn-default">Add Response</button>
                <div class="clearfix"></div>
            </div>

            @elseif($question->question_type == 'TRUE_FALSE')

            <div class="true-false-response"
                <span class="label label-success">Correct Answer</span>
                <select class="true-false-option form-control">
                    <option value="TRUE" selected>True</option>
                    <option value="FALSE">False</option>
                </select>
            </div>

            @endif
        </div>
    </div>
</li>
