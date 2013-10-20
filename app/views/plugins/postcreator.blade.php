<div class="post-creator-holder">
    @if(Auth::user()->account_type == 1)
    <ul class="nav nav-tabs" id="post_creator_options">
        <li class="<?php echo (isset($quiz)) ? null : 'active'; ?>"><a href="#note"><i class="icon-edit"></i> Note</a></li>
        <li><a href="#alert"><i class="icon-exclamation-sign"></i> Alert</a></li>
        <li><a href="#assignment"><i class="icon-check"></i> Assigment</a></li>
        <li class="<?php echo (isset($quiz)) ? 'active' : null; ?>"><a href="#quiz"><i class="icon-question-sign"></i> Quiz</a></li>
    </ul>
    @endif
    <div class="tab-content">
        <div class="tab-pane well <?php echo (isset($quiz)) ? null : 'active'; ?>" id="note">
            {{ Form::open(array('url'=>'ajax/post_creator/create_note')) }}
                <div class="form-group">
                    <div class="alert"></div>
                    <textarea name="note-content" id="note_content" class="postcreator-textarea form-control"
                    placeholder="Type your note here..."></textarea>
                </div>

                <div class="postcreator-hidden">
                    <div class="form-group">
                        <div class="alert"></div>
                        <select name="note-recipients[]" class="post-recipients"
                        id="note_recipients" data-placeholder="Send to..."
                        <?php echo (Auth::user()->account_type == 1) ? 'multiple="true"' : null; ?>>
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
                        <ul class="postcreator-controls pull-left">
                            <li><input type="file" name="note-file" id="note_file"></li>
                        </ul>

                        <div class="postcreator-buttons pull-right">
                            <a href="">Cancel</a>
                            <span class="postcreator-send-or">or</span>
                            <button type="submit" id="submit_note" class="btn btn-primary">
                                Send
                            </button>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>
            {{ Form::close() }}
        </div>

        <div class="tab-pane well" id="alert">
            {{ Form::open(array('url'=>'ajax/post_creator/create_alert')) }}
                <div class="form-group">
                    <div class="alert"></div>
                    <textarea name="alert-content" id="alert_content" class="postcreator-textarea form-control"
                    placeholder="Type your alert (140 character max)..." maxlength="140"></textarea>
                </div>

                <div class="postcreator-hidden">
                    <div class="form-group">
                        <div class="alert"></div>
                        <select name="alert-recipients[]" class="post-recipients"
                        id="alert_recipients" multiple="true" data-placeholder="Send to...">
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
                        <ul class="postcreator-controls pull-left">
                            <li></li>
                        </ul>

                        <div class="postcreator-buttons pull-right">
                            <a href="">Cancel</a>
                            <span class="postcreator-send-or">or</span>
                            <button type="submit" id="submit_alert" class="btn btn-primary">
                                Send
                            </button>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>
            {{ Form::close() }}
        </div>

        <div class="tab-pane well" id="assignment">
            Assignment
        </div>

        <div class="tab-pane well <?php echo (isset($quiz)) ? 'active' : null; ?>" id="quiz">
            @if(isset($quiz))
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
            @endif
            <div class="quiz-first-choices"
            <?php echo (isset($quiz)) ? 'style="display: none;"' : null; ?>>
                <a href="/quiz-creator" class="btn btn-primary">Create a Quiz</a>
                <span class="postcreator-or">or</span>
                <a href="#">Load a previously created Quiz</a>
            </div>
        </div>
    </div>
</div>
