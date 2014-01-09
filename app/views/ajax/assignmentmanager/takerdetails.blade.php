<div class="manager-header">
    {{ Helper::avatar(42, "small", "img-rounded pull-left", $user->id) }}
    <div class="taker-details pull-left">
        <h4>{{ $user->name }}</h4>
        @if(empty($response))
        <div class="not-turned-in-message response-status">Not turned in</div>
        @endif
        @if(!empty($response))
        <div class="response-status">
            Submitted on {{ date('M d,Y h:i A', strtotime($response->created_at)) }}
        </div>
        @endif
    </div>
    @if(!empty($response))
    <div class="taker-score pull-right">
        @if($assignment->total_score == 0 && $response->score == 0)
        <div class="no-score-set">
            {{ Form::open(array(
                'class' => 'no-score-form response-score-form',
                'data-assignment-id' => $assignment->assignment_id))
            }}
                <div class="form-group">
                    <input type="text" name="user-score"
                    class="form-control user-score">
                </div>
                <span class="seperator">/</span>
                <div class="form-group">
                    <input type="text" name="total-score"
                    class="form-control total-score">
                </div>

                <input type="hidden" name="user-id" value="{{ $user->id }}">
                <input type="hidden" name="assignment-id"
                value="{{ $assignment->assignment_id }}">
                <input type="hidden" name="assignment-response-id"
                value="{{ $response->assignment_response_id }}">

                <button type="submit" class="btn btn-primary submit-grade"
                data-assignment-id="{{ $assignment->assignment_id }}">Grade</button>
            {{ Form::close() }}
        </div>
        @endif
        @if($assignment->total_score != 0 && $response->score == 0)
        <div class="set-user-score">
            {{ Form::open(array(
                'class' => 'set-score-form response-score-form',
                'data-assignment-id' => $assignment->assignment_id))
            }}
                <div class="form-group">
                    <input type="text" name="user-score"
                    class="form-control user-score">
                </div>
                <span class="seperator">/</span>
                <div class="form-group">
                    <input type="text" name="total-score"
                    class="form-control total-score" value="{{ $assignment->total_score }}">
                </div>

                <input type="hidden" name="user-id" value="{{ $user->id }}">
                <input type="hidden" name="assignment-id"
                value="{{ $assignment->assignment_id }}">
                <input type="hidden" name="assignment-response-id"
                value="{{ $response->assignment_response_id }}">
                <button type="submit" class="btn btn-primary set-grade"
                data-assignment-id="{{ $assignment->assignment_id }}">Grade</button>
            {{ Form::close() }}
        </div>
        @endif
        @if($assignment->total_score != 0 && $response->score != 0)
        <div class="user-total-score">
            <span class="user-score">{{ $response->score }}</span>
            <span class="seperator">/</span>
            <span class="total-score">{{ $assignment->total_score }}</span>
        </div>
        @endif
    </div>
    @endif
    <div class="clearfix"></div>
</div>
@if(empty($response))
<div class="no-submission">
    <span>This assignment has not been turned in via eLinet.</span>
</div>
@endif
@if(!empty($response))
<div class="assignment-response"><span>{{{ $response->response }}}</span></div>
@endif
