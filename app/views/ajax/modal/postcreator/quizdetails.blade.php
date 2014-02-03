{{ Form::open(array('url'=>'ajax/post_creator/create_quiz')) }}
    <div class="quiz-details">
        <span class="quiz-title"><?php echo $quiz->title; ?></span>
        <a href="#">Edit</a>
        <span class="post-creator-divider">|</span>
        <a href="#">Select a different Quiz</a>
    </div>

    <div class="quiz-due-date form-group">
        <div class="alert"></div>
        <input type="text" name="due-date" class="form-control"
        id="quiz_due_date" placeholder="due date">
    </div>

    <div class="form-group">
        <div class="alert"></div>
        <select name="quiz-recipients[]" class="post-recipients"
        id="quiz_recipients" multiple="true" data-placeholder="Send to...">
            @if(!empty($groups))
            @foreach($groups as $group)
            @if(isset($groupDetails))
            <option value="{{ $group->group_id }}-group"
            <?php echo ($groupDetails->group_id == $group->group_id) ? 'selected' : null; ?>>{{ $group->group_name }}</option>
            @else
            <option value="{{ $group->group_id }}-group">{{ $group->group_name }}</option>
            @endif
            @endforeach
            @endif
            @if(!empty($groupMembers))
            @foreach($groupMembers as $groupMember)
            <option value="{{ $groupMember->id }}-user">{{ $groupMember->name }}</option>
            @endforeach
            @endif
        </select>
    </div>

    <div class="postcreator-form-controls">
        <input type="hidden" name="quiz-id" value="{{ $quiz->quiz_id }}">
        <div class="postcreator-buttons pull-right">
            <button type="submit" id="submit_quiz" class="btn btn-primary">
                Send
            </button>
        </div>
    </div>
    <div class="clearfix"></div>
{{ Form::close() }}
