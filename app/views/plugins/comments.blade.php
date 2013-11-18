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
                    <span class="text-muted">&bull;</span>
                    <span class="subtext">{{ $commentTimestamp }}</span>
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

<div class="comment-form-holder" data-post-id="{{ $post->post_id }}"
<?php echo ($comments->isEmpty()) ? 'style="display: none;"' : null; ?>>
    <form class="comment-form" data-post-id="{{ $post->post_id }}">
        <textarea name="post-comment" class="post-comment form-control"
        data-post-id="{{ $post->post_id }}"></textarea>
    </form>
</div>
