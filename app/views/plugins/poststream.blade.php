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
        <li class="post-holder">
            <a href="#" class="writer-profile">
                @if(empty($post->avatar))
                <img src="/assets/images/anon.png" width="50" class="img-rounded pull-left">
                @else
                <img src="/assets/avatars/{{ $post->hashed_id }}/{{ $post->avatar_normal }}"
                width="50" class="img-rounded pull-left">
                @endif
            </a>
            <div class="post-content pull-left">
                <div class="post-content-header">
                    <a href="#" class="post-sender-name">
                        @if($post->account_type == '1')
                        {{ $post->salutation.' '.$post->name }}
                        @else
                        {{ $post->name }}
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
                    <div class="{{ $post->post_type }}">
                    <?php
                    switch($post->post_type) {
                        case 'note' :
                            echo nl2br(htmlentities(($post->note_content)));
                            break;
                        case 'alert' :
                            echo nl2br(htmlentities(($post->alert_content)));
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
                    <li><a href="#"><i class="icon-thumbs-up-alt"></i> Like it</a></li>
                    <li><a href="#"><i class="icon-comment-alt"></i> Reply</a></li>
                    <li><a href="#"><i class="icon-time"></i> August 25, 2013</a></li>
                </ul>
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
