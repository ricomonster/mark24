<li class="question-wrapper">
    <div class="question-prompt-wrapper">
        <div class="form-group">
            <label class="question-prompt">Question Prompt:</label>
            <textarea name="question-prompt" class="form-control question-prompt"
            data-question-id="{{ $question->question_id }}"></textarea>
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
    </div>
</li>
