@extends('templates.master')

@section('title')
Hello
@stop

@section('internalCss')
<style type="text/css">
.post-wrapper .post-holder {
    background-color: #fff;
    border: 1px solid #dfe4e8;
    margin: auto;
    width: 700px;
}

.post-wrapper .post-holder .writer-profile img { margin: 20px 0 0 20px; }
.post-wrapper .post-holder .post-content { margin: 20px 0 20px 20px; width: 84%; }
.post-wrapper .post-holder .post-content .post-content-container { padding: 10px 0; }
.post-wrapper .post-holder .post-content .post-content-container .alert {
    font-weight: bold;
    margin: 0;
    padding: 0;
}

.post-wrapper .post-holder .post-content .post-content-container .quiz .quiz-button-wrapper {
    border: 1px solid #ccc;
    border-radius: 3px;
    border-bottom-right-radius: 0;
    border-bottom-left-radius: 0;
    padding: 10px;
    margin-top: 5px;
}

.post-wrapper .post-holder .post-content .post-content-container .quiz .question-count-wrapper {
    border: 1px solid #ccc;
    border-top: 0;
    border-radius: 3px;
    border-top-right-radius: 0;
    border-top-left-radius: 0;
    padding: 10px;
}

.post-wrapper .no-post-found { font-size: 16px; padding: 50px 20px; }
.post-wrapper .post-holder .post-content .dropdown-post-options { margin-right: 10px; }
.post-wrapper .post-holder .post-content .dropdown-post-options a { color: #383d48; }

.post-content .post-content-container .edit-post-form { display: none; }

.post-etcs { background-color: #f3f5f6; border-top: 1px solid #e0e3e7; padding: 10px 90px 10px; }
.post-etcs ul li { display: inline-block; margin-right: 10px; }
.post-etcs ul li a { color: #869399; font-size: 13px; }
.post-etcs ul li a:hover { text-decoration: none; }

.post-comment-holder { background-color: #f3f5f6; padding: 0; }
.post-comment-holder .comment-stream { list-style: none; padding: 0; }
.post-comment-holder .comment-stream li { border-top: 1px solid #e0e3e7; padding: 10px 0 0 34px; }
.post-comment-holder .comment-stream li .comment-content-holder { margin-left: 20px; }

.comment-form-holder { background-color: #f3f5f6; border-top: 1px solid #e0e3e7; padding: 10px 30px 10px 0; }
.comment-form-holder .comment-form { margin-left: 90px; }
.comment-form-holder .comment-form .post-comment {
    display: inline;
    height: 32px;
    overflow: hidden;
    resize: none;
}
.comment-form-holder .comment-form .submit-comment {
    /*display: none;*/
    height: 32px;
    width: 100px;
}
</style>
@stop

@section('content')
<?php $recipients = PostRecipient::getRecipients($post->post_id); ?>
<?php $postTimestamp = Helper::timestamp($post->post_timestamp); ?>

<div class="post-wrapper">
    <div class="post-holder" data-post-id="{{ $post->post_id }}">
        <a href="/profile/{{ $post->username }}" class="writer-profile">
            {{ Helper::avatar(50, "small", "img-rounded pull-left", $post->id) }}
        </a>
        <div class="post-content pull-left">

            <div class="dropdown dropdown-post-options pull-right">
                <a data-toggle="dropdown" href="#"><i class="fa fa-gear"></i></a>
                <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                    @if(Auth::user()->id === $post->id)
                    <li><a href="#" class="delete-post"
                    data-post-id="{{ $post->post_id }}">Delete Post</a></li>
                    @if($post->post_type != 'quiz')
                    <li><a href="#" class="edit-post"
                    data-post-id="{{ $post->post_id }}">Edit Post</a></li>
                    @endif
                    @endif
                    <li><a href="#" class="link-post"
                    data-post-id="{{ $post->post_id }}">Link to this Post</a></li>
                </ul>
            </div>

            <div class="post-content-header">
                <a href="/profile/{{ $post->username }}" class="post-sender-name">
                    @if($post->id == Auth::user()->id)
                    Me
                    @else
                    @if($post->account_type == '1')
                    {{ $post->salutation.' '.$post->name }}
                    @else
                    {{ $post->name }}
                    @endif
                    @endif
                </a>
                <span class="sender-to-receiver">to</span>
                <?php $groupCount = (!empty($recipients['groups'])) ? count($recipients['groups']) : null; ?>
                <?php $userCount = (!empty($recipients['users'])) ? count($recipients['users']) : null; ?>

                @if(!empty($recipients['groups']))
                @foreach($recipients['groups'] as $key => $groupRecipient)
                @if($key != $groupCount -1 || $userCount != 0)
                <a href="#" class="post-receiver-name">{{ $groupRecipient->group_name }}</a><span class="post-receiver-comma">,</span>
                @else
                <a href="#" class="post-receiver-name">{{ $groupRecipient->group_name }}</a>
                @endif
                @endforeach
                @endif

                @if(!empty($recipients['users']))
                @foreach($recipients['users'] as $key => $userRecipient)
                @if($key != $userCount -1)
                <a href="#" class="post-receiver-name">
                    <?php if($userRecipient->account_type == 1) { echo $userRecipient->salutation.'. '; } ?>
                    {{ $userRecipient->name }}
                </a><span class="post-receiver-comma">,</span>
                @else
                <a href="#" class="post-receiver-name">
                    <?php if($userRecipient->account_type == 1) { echo $userRecipient->salutation.'. '; } ?>
                    {{ $userRecipient->name }}
                </a>
                @endif
                @endforeach
                @endif
            </div>

            <div class="post-content-container">
                <div class="post {{ $post->post_type }}">
                <?php
                switch($post->post_type) {
                    case 'note' :
                        echo nl2br(htmlentities(($post->note_content)));
                        $content = $post->note_content;
                        break;
                    case 'alert' :
                        echo nl2br(htmlentities(($post->alert_content)));
                        $content = $post->alert_content;
                        break;
                    case 'quiz' :
                        $quizDetails = Helper::getQuizDetails($post->quiz_id);
                ?>
                    <strong class="quiz-title">{{ $quizDetails['title'] }}</strong>
                    <div class="quiz-button-wrapper">
                        <?php $turnedIn = Helper::getTakenDetails($post->quiz_id); ?>
                        @if(Auth::user()->account_type == 1)
                        <a href="/quiz-manager/{{ $post->quiz_id }}" class="btn btn-default">
                            Turned In ({{ $turnedIn['takers'] }})
                        </a>
                        <span class="due-date">
                            Due {{ date('M d, Y', strtotime($post->quiz_due_date)) }}
                        </span>
                        @endif

                        @if(Auth::user()->account_type == 2)
                        <?php $taken = Helper::checkQuizTaken($post->quiz_id); ?>
                        @if(empty($taken))
                        <a href="/quiz-sheet/{{ $post->quiz_id }}" class="btn btn-default">
                            Take Quiz
                        </a>
                        <span class="due-date">Due {{ date('M d, Y', strtotime($post->
                        quiz_due_date)) }}</span>
                        @endif
                        @if(!empty($taken))
                        <a href="/quiz-result/{{ $post->quiz_id }}" class="btn btn-default">
                            Quiz Result
                        </a>
                        @endif

                        @endif
                    </div>
                    <div class="question-count-wrapper">
                        <strong class="count-text">{{ $turnedIn['count'] }}</strong>
                    </div>
                <?php
                        break;
                    default :
                        break;
                }
                ?>
                </div>
                @if($post->post_type != 'quiz')
                {{ Form::open(array(
                    'url' => '/ajax/post_creator/update-post',
                    'class' => 'edit-post-form',
                    'data-post-id' => $post->post_id))
                }}
                    <div class="form-group">
                        <textarea name="message-post" class="form-control message-post"
                        data-post-id="{{ $post->post_id }}">{{ $content }}</textarea>
                    </div>
                    <input type="hidden" name='post-id' value="{{ $post->post_id }}">

                    <button class="btn btn-primary save-edit-post"
                    data-post-id="{{ $post->post_id }}">Save</button>
                    <button class="btn btn-default cancel-edit-post"
                    data-post-id="{{ $post->post_id }}">Cancel</button>
                {{ Form::close() }}
                @endif
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="post-etcs">
            <ul class="post-etcs-holder">
                <li><a href="#"><i class="fa fa-thumbs-up"></i> Like it</a></li>
                <li>
                    <a href="#" class="show-comment-form" data-post-id="{{ $post->post_id }}">
                        <i class="fa fa-comment"></i> Reply
                    </a>
                </li>
                <li><a href="#"><i class="fa fa-clock-o"></i> {{ $postTimestamp }}</a></li>
            </ul>
        </div>
        @include('plugins.comments')
    </div>
</div>
@stop

@section('js')
<script src="/assets/js/sitefunc/comment.creator.js"></script>
<script src="/assets/js/sitefunc/poststream.js"></script>
<script>

</script>
@stop
