<div class="post-creator-holder">
    <div class="overlay"><p>Sending...</p></div>
    @if(Auth::user()->account_type == 1)
    <ul class="nav nav-tabs" id="post_creator_options">
        <li class="<?php echo (isset($quiz)) ? null : 'active'; ?>"><a href="#note"><i class="fa fa-edit"></i> <span>Note</span></a></li>
        <li><a href="#alert"><i class="fa fa-exclamation-triangle"></i> <span>Alert</span></a></li>
        <li><a href="#assignment"><i class="fa fa-check-circle"></i> <span>Assigment</span></a></li>
        <li class="<?php echo (isset($quiz)) ? 'active' : null; ?>"><a href="#quiz"><i class="fa fa-question-circle"></i> <span>Quiz</span></a></li>
    </ul>
    @endif
    <div class="tab-content">
        <div class="tab-pane well <?php echo (isset($quiz)) ? null : 'active'; ?>" id="note">
            <div class="note-errors alert alert-danger" style="display:none;"></div>
            {{ Form::open(array('url'=>'ajax/post_creator/create_note', 'files' => true)) }}
                <div class="form-group">
                    <textarea name="note-content" id="note_content" class="postcreator-textarea form-control"
                    placeholder="Type your note here..."></textarea>
                </div>

                <div class="postcreator-hidden">
                    <div class="form-group">
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

                    <div class="attached-files">
                        <div class="notes-progress progress">
                            <div class="progress-bar progress-bar-success"></div>
                        </div>
                        <ul class="notes-files files"></ul>
                    </div>

                    <div class="postcreator-form-controls">
                        <ul class="postcreator-controls pull-left">
                            <li>
                                <label class="fileuploader-container">
                                    <i class="fileuploader-icon fa fa-paperclip"></i>
                                    <input class="fileupload-notes" type="file"
                                    name="files" multiple>
                                </label>
                            </li>
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
            <div class="alert-errors alert alert-danger" style="display:none;"></div>
            {{ Form::open(array('url'=>'ajax/post_creator/create_alert')) }}
                <div class="form-group">
                    <textarea name="alert-content" id="alert_content" class="postcreator-textarea form-control"
                    placeholder="Type your alert (140 character max)..." maxlength="140"></textarea>
                </div>

                <div class="postcreator-hidden">
                    <div class="form-group">
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
            <div class="assignment-errors alert alert-danger" style="display:none;"></div>
            {{ Form::open(array('url' => 'ajax/post_creator/create_assignment')) }}
                <div class="assignment-details">
                    <div class="form-group">
                        <input type="text" name="assignment-title" id="assignment_title"
                        class="form-control assignment-title pull-left"
                        placeholder="Assignment title">
                    </div>
                    <a href="#" class="load-assignment btn btn-default">Load Assignment</a>
                    <div class="input-group">
                        <input type="text" name="due-date" class="form-control assignment-due-date pull-left"
                        placeholder="due date" id="assignment_due_date">
                        <span class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </span>
                    </div>
                    <div class="clearfix"></div>
                </div>

                <div class="postcreator-hidden">
                    <div class="form-group">
                        <input type="text" name="assignment-description" class="form-control"
                        placeholder="Describe the assignment" id="assignment_description">
                    </div>
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="assignment-lock" class="assignment-lock"
                            value="1" id="assignment_lock"> Lock this assignment after its due date
                        </label>
                    </div>
                    <div class="form-group">
                        <select name="assignment-recipients[]" class="post-recipients"
                        id="assignment_recipients" multiple="true" data-placeholder="Send to...">
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

                    <div class="attached-files">
                        <div class="assignment-progress progress">
                            <div class="progress-bar progress-bar-success"></div>
                        </div>
                        <ul class="assignment-files files"></ul>
                    </div>

                    <div class="postcreator-form-controls">
                        <ul class="postcreator-controls pull-left">
                            <li>
                                <label class="fileuploader-container">
                                    <i class="fileuploader-icon fa fa-paperclip"></i>
                                    <input class="fileupload-assignment" type="file"
                                    name="files" multiple>
                                </label>
                            </li>
                        </ul>

                        <div class="postcreator-buttons pull-right">
                            <a href="">Cancel</a>
                            <span class="postcreator-send-or">or</span>
                            <button type="submit" id="submit_assignment" class="btn btn-primary">
                                Send
                            </button>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            {{ Form::close() }}
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
                <a href="#" id="show_quiz_list">Load a previously created Quiz</a>
            </div>
        </div>
    </div>
</div>
