<div class="manager-header">
    {{ Helper::avatar(42, "small", "img-rounded pull-left", $userDetails->id) }}
    
    <div class="taker-details pull-left">
        <h4>{{ $userDetails->name }}</h4>
        <div class="quiz-taken-details text-muted">
            <span>Time Taken: 1:00:00:00</span>
            <span>|</span>
            <span>Turned in Oct 15, 2013 3:06 PM</span>
        </div>
        <div class="taken-controls">
            <span>Graded</span>
            <span>|</span>
            <a href="#">Delete</a>
        </div>
    </div>
    
    <div class="taker-stats pull-right">
        <h2>
            {{ (empty($questions)) ? '0' : $takerDetails->score; }}
            /
            {{ $quiz->total_score }}
        </h2>
        <span class="text-muted">Total Points</span>
    </div>
    <div class="clearfix"></div>
</div>
@if(empty($questions))
<div class="user-no-answer-wrapper">User hasn't answered this shit.</div>
@endif
@if(!empty($questions))
<ul class="nav nav-tabs quiz-item-pagination">
    @foreach($questions['list'] as $key => $question)
    <li class="<?php echo ($key == 0) ? 'active' : null; ?>">
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
    id="{{ $key + 1 }}">
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
            @if($question['question']['question_type'] == 'TRUE_FALSE' || $answer['is_correct'] === 'TRUE')
            <div class="response-true-false">
                @if(empty($answer))
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
        </div>
    </div>
    @endforeach
</div>
@endif