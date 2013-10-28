<div class="post-stream-holder well">
    <div class="stream-title">
        @if(isset($groupDetails))
        <h4>Group Posts</h4>
        @else
        <h4>Latest Posts</h4>
        @endif
    </div>

    <ul class="post-stream">
        @if(!empty($posts))
        @foreach($posts as $post)
        <?php $recipients = PostRecipient::getRecipients($post->post_id); ?>
        <?php $postTimestamp = Helper::timestamp($post->post_timestamp); ?>
        <li class="post-holder">
            <a href="/profile/{{ $post->username }}" class="writer-profile">
                {{ Helper::avatar(50, "small", "img-rounded pull-left", $post->id) }}
            </a>
            <div class="post-content pull-left">

                <div class="dropdown dropdown-post-options pull-right">
                    <a data-toggle="dropdown" href="#"><i class="fa fa-gear"></i></a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                        <li><a href="#">Delete Post</a></li>
                        @if($post->post_type != 'quiz')
                        <li><a href="#">Edit Post</a></li>
                        @endif
                        <li><a href="#">Link to this Post</a></li>
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
                        case 'note'
                            echo nl2br(htmlentities(($post->note_content)));
                            break;
                        case 'alert' :
                            echo nl2br(htmlentities(($post->alert_content)));
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
                            <span class="due-date">Due {{ date('M d, Y', strtotime($post->quiz_due_date)) }}</span>
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
                </div>
            </div>
            <div class="clearfix"></div>
            <div class="post-etcs">
                <ul class="post-etcs-holder">
                    <li><a href="#"><i class="fa fa-thumbs-up"></i> Like it</a></li>
                    <li><a href="#"><i class="fa fa-comment"></i> Reply</a></li>
                    <li><a href="#"><i class="fa fa-clock-o"></i> {{ $postTimestamp }}</a></li>
                </ul>
            </div>
            <?php $comments = Helper::getComments($post->post_id); ?>
            <div class="post-comment-holder" data-post-id="{{ $post->post_id }}"
            <?php echo ($comments->isEmpty()) ? 'style="display: none;"' : null; ?>>
                <ul class="comment-stream">
                    @foreach($comments as $comment)
                    <?php $commentTimestamp = Helper::timestamp($comment->comment_timestamp); ?>
                    <li data-comment-id="{{ $comment->commment_id }}">
                        <a href="/profile/{{ $comment->username }}">
                            {{ Helper::avatar(35, "small", "img-rounded pull-left", $comment->id) }}
                        </a>
                        <div class="comment-content-holder pull-left">
                            <div class="commenter-details">
                                @if($comment->id == Auth::user()->id)
                                <a href="/profile/{{ $comment->username }}">Me</a>
                                @else
                                <a href="/profile/{{ $comment->username }}">{{ $comment->name }}</a>
                                @endif
                                <span class="subtext"> said {{ $commentTimestamp }}:</span>
                            </div>
                            <p class="comment-text">
                                <?php echo nl2br(htmlentities(($comment->comment))); ?>
                            </p>
                        </div>
                        <div class="clearfix"></div>
                    </li>
                    @endforeach
                </ul>
                <div class="clearfix"></div>
            </div>

            <div class="comment-form-holder">
                <form class="comment-form" data-post-id="{{ $post->post_id }}">
                    <textarea name="post-comment" class="post-comment form-control"
                    data-post-id="{{ $post->post_id }}"></textarea>
                    <button class="btn btn-primary submit-comment" disabled
                    data-post-id="{{ $post->post_id }}">Send</button>
                </form>
            </div>
        </li>
        @endforeach
        @else
        <li class="post-holder no-post-found">
            No post found :(
        </li>
        @endif
    </ul>
</div>
