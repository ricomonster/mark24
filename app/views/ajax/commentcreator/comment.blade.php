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