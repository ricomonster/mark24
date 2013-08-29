<?php $recipients = PostRecipient::getRecipients($post->post_id); ?>
<li class="post-holder">
    <a href="#"><img src="/assets/images/anon.png" width="50" class="img-rounded pull-left"></a>
    <div class="post-content pull-left">
        <div class="post-content-header">
            <a href="#" class="post-sender-name">
                @if($post->account_type == '1')
                {{ $post->salutation.' '.$post->name }}
                @else
                {{ $post->name }}
                @endif
            </a>
            <span class="subtext sender-to-receiver">to</span>
            <?php $groupCount = (!empty($recipients['groups'])) ? count($recipients['groups']) : null; ?>
            <?php $userCount = (!empty($recipients['users'])) ? count($recipients['users']) : null; ?>

            @if(!empty($recipients['groups']))
            @foreach($recipients['groups'] as $key => $groupRecipient)
            @if($key != $groupCount -1 || $userCount != 0)
            <a href="#" class="post-receiver-name">{{ $groupRecipient->group_name }}</a><span class="subtext post-receiver-comma">,</span>
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
            </a><span class="subtext post-receiver-comma">,</span>
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
            <?php
                switch($post->post_type) {
                    case 'note' :
                        echo $post->note_content;
                        break;
                    default :
                        break;
                }
            ?>
        </div>
    </div>
    <div class="post-etcs pull-left">
        <ul class="post-etcs-holder">
            <li><a href="#"><i class="icon-thumbs-up-alt"></i> Like it</a></li>
            <li><a href="#"><i class="icon-comment-alt"></i> Reply</a></li>
            <li><a href="#"><i class="icon-time"></i> August 25, 2013</a></li>
        </ul>
    </div>
    <div class="clearfix"></div>
</li>
