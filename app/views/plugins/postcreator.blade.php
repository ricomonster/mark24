<div class="post-creator-holder">
    <ul class="nav nav-tabs" id="post_creator_options">
        <li class="active"><a href="#note"><i class="icon-edit"></i> Note</a></li>
        <li><a href="#alert"><i class="icon-exclamation-sign"></i> Alert</a></li>
        <li><a href="#assignment"><i class="icon-check"></i> Assigment</a></li>
        <li><a href="#quiz"><i class="icon-question-sign"></i> Quiz</a></li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane well active" id="note">
            {{ Form::open(array('url'=>'ajax/post_creator/create_note')) }}
                <textarea name="note" id="note_holder" class="form-control"
                placeholder="Type your note here..."></textarea>
            {{ Form::close() }}
        </div>
        <div class="tab-pane well" id="alert">
            Alert
        </div>
        <div class="tab-pane well" id="assignment">
            Assignment
        </div>
        <div class="tab-pane well" id="quiz">
            Quiz
        </div>
    </div>
</div>
