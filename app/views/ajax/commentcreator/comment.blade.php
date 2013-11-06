<li data-comment-id="{{ $comment->commment_id }}">
    <a href="#">
        @if(empty($comment->avatar))
        <img src="/assets/images/anon.png" width="35" class="img-rounded pull-left">
        @else
        <img src="/assets/avatars/{{ $comment->hashed_id }}/{{ $comment->avatar_small }}"
        width="35" class="img-rounded pull-left">
        @endif
    </a>
    <div class="comment-content-holder pull-left">
        <div class="commenter-details">
            @if($comment->id == Auth::user()->id)
            <a href="#">Me</a>
            @else
            <a href="#">{{ $comment->name }}</a>
            @endif
            <span class="subtext"> said 1 hour ago:</span>
        </div>
        <p class="comment-text">
            <?php echo nl2br(htmlentities(($comment->comment))); ?>
        </p>
    </div>
    <div class="clearfix"></div>
</li>
