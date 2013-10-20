@extends('templates.master')

@section('title')
The Forum
@stop

@section('internalCss')
<style>
/*.the-forum .forum-body { background-color: transparent; margin: 0; padding: 0; }*/
.the-forum .forum-body .forum-title {
    background-color: #fff;
    padding: 10px 20px;
}

.the-forum .post-thread-link { margin-bottom: 10px; }
.the-forum .thread-actions-holder { margin-bottom: 10px; }
.the-forum .thread-actions-holder .title-holder {
    background-color: #757f93;
    color: #ffffff;
    padding: 12px;
}

.the-forum .thread-actions-holder ul li { margin: 0; }
.the-forum .thread-actions-holder ul li a {
    background-color: #ffffff;
    border: 1px solid #dfe4e8 !important;
    border-top: 0 !important;
    border-radius: 0;
}

.the-forum .forum-category-holder { margin-bottom: 10px; }
.the-forum .forum-category-holder .title-holder {
    background-color: #757f93;
    color: #ffffff;
    padding: 12px;
}

.the-forum .forum-category-holder ul li { margin: 0; }
.the-forum .forum-category-holder ul li a {
    background-color: #ffffff;
    border: 1px solid #dfe4e8 !important;
    border-top: 0 !important;
    border-radius: 0;
}

.the-forum .forum-category-holder ul li.active a {
    background-color: #f3f5f7;
    color: #2a6496;
}

.forum-thread-body { background-color: inherit; border: 0; margin: 0; padding: 0; }
.forum-thread-body .forum-thread-stream {}
.forum-thread-stream { margin: 0; list-style: none; padding: 0; }
.forum-thread-stream li { background-color: #ffffff; padding-bottom: 20px; }
.forum-thread-stream .thread-holder { border: 1px solid #dfe4e8; margin-bottom: 20px; }
.forum-thread-stream .thread-holder .author-details { margin: 20px 0 0 20px; text-align: center; width: 80px; }
.forum-thread-stream .thread-holder .author-details img { margin-bottom: 10px; }
.forum-thread-stream .thread-holder .author-details .user-type { display: block; }
.forum-thread-stream .thread-holder .thread-details-holder {
    margin: 20px 0 20px 20px;
    width: 80%;
}

.forum-thread-stream .thread-holder .thread-details-holder .thread-holder-title {
    border-bottom: 1px solid #dfe4e8;
    font-size: 28px;
    margin-bottom: 20px;
    padding-bottom: 10px;
}

.forum-thread-stream .thread-holder .thread-details-holder .thread-holder-description {
    margin-top: 10px;
}

.forum-thread-stream .thread-reply-holder { border: 1px solid #dfe4e8; margin-bottom: 20px; }
.forum-thread-stream .thread-reply-holder .author-details { margin: 20px 0 0 20px; text-align: center; width: 80px; }
.forum-thread-stream .thread-reply-holder .author-details img { margin-bottom: 10px; }
.forum-thread-stream .thread-reply-holder .author-details .user-type { display: block; }
.forum-thread-stream .thread-reply-holder .thread-reply-details-holder {
    margin: 20px 0 20px 20px;
    width: 80%;
}
.forum-thread-stream .thread-reply-holder .thread-reply-details-holder .thread-reply-description {
    margin-top: 10px;
}

.forum-thread-stream .reply-to-thread-holder { border: 1px solid #dfe4e8; margin-bottom: 20px; }
.forum-thread-stream .reply-to-thread-holder .author-details { margin: 20px 0 0 20px; text-align: center; width: 80px; }
.forum-thread-stream .reply-to-thread-holder .author-details img { margin-bottom: 10px; }
.forum-thread-stream .reply-to-thread-holder .author-details .user-type { display: block; }
.forum-thread-stream .reply-to-thread-holder .reply-to-thread-form {
    margin: 20px 0 0 20px;
    width: 80%;

}
.forum-thread-stream .reply-to-thread-holder .reply-to-thread-form textarea {
    height: 200px;
    resize: none;
}
</style>
@stop

@section('content')

<div class="message-holder"><span></span></div>

<div class="modal fade" id="the_modal" tabindex="-1" role="dialog"
aria-labelledby="the_modal_label" aria-hidden="true"></div>

<div class="row the-forum">
    <div class="col-md-3">
        <a href="/the-forum/add-thread" class="btn btn-info btn-large btn-block post-thread-link">
            Post a Thread
        </a>

        <div class="thread-actions-holder">
            <div class="title-holder">Thread Actions</div>
            <ul class="nav nav-pills nav-stacked">
                @if(empty($followed))
                <li><a href="#">Follow</a></li>
                @else
                <li><a href="#">Unfollow</a></li>
                @endif
            </ul>
        </div>

        <div class="forum-category-holder">
            <div class="title-holder">Forum Categories</div>
            <ul class="nav nav-pills nav-stacked">
                @foreach($categories as $category)
                <li
                class="<?php echo ($thread->category_id == $category->forum_category_id) ? 'active' : null; ?>">
                    <a href="/the-forum/{{ $category->seo_name }}">
                        {{ $category->category_name }}
                    </a>
                </li>
                @endforeach
            </ul>
        </div>

        @if(Auth::user()->account_type == 1)
        <a href="#" class="btn btn-primary btn-large btn-block add-forum-category">
            Add Forum Category
        </a>
        @endif
    </div>

    <div class="col-md-9">
        <div class="well forum-thread-body">
            <ul class="forum-thread-stream">
                <li class="thread-holder">
                    <?php $timestamp = Helper::timestamp($thread->timestamp); ?>
                    <div class="author-details pull-left">
                        @if($thread->avatar == 'default_avatar.png')
                        <img src="/assets/images/default_avatar.png" width="70" class="img-rounded">
                        @else
                        <img src="/assets/avatars/{{ $thread->hashed_id }}/{{ $thread->avatar_normal }}"
                        width="70" class="img-rounded">
                        @endif
                        <a href="/profile/{{ $thread->username }}">{{ $thread->username }}</a>
                        <span class="user-type text-muted">
                            @if($thread->account_type == 1)
                            Teacher
                            @endif
                            @if($thread->account_type == 2)
                            Student
                            @endif
                        </span>
                        <span class="text-muted">{{ $thread->forum_posts }} posts</span>
                    </div>
                    <div class="thread-details-holder pull-left">
                        <div class="thread-holder-title">{{ $thread->title }}</div>
                        <span class="thread-holder-timestamp text-muted">{{ $timestamp }}</span>
                        <div class="thread-holder-description">
                            <?php echo nl2br($thread->description); ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </li>

                @if(!$replies->isEmpty())
                @foreach($replies as $reply)
                <?php $timestamp = Helper::timestamp($reply->reply_timestamp); ?>
                <li class="thread-reply-holder">
                    <div class="author-details pull-left">
                        @if($reply->avatar == 'default_avatar.png')
                        <img src="/assets/images/default_avatar.png" width="70" class="img-rounded">
                        @else
                        <img src="/assets/avatars/{{ $reply->hashed_id }}/{{ $reply->avatar_normal }}"
                        width="70" class="img-rounded">
                        @endif
                        <a href="/profile/{{ $reply->username }}">{{ $reply->username }}</a>
                        <span class="user-type text-muted">
                            @if($reply->account_type == 1)
                            Teacher
                            @endif
                            @if($reply->account_type == 2)
                            Student
                            @endif
                        </span>
                        <span class="text-muted">{{ $reply->forum_posts }} posts</span>
                    </div>
                    <div class="thread-reply-details-holder pull-left">
                        <span class="thread-reply-timestamp text-muted">{{ $timestamp }}</span>
                        <div class="thread-reply-description">
                            <?php echo nl2br($reply->reply); ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </li>
                @endforeach
                @endif

                <li class="reply-to-thread-holder">
                    <div class="author-details pull-left">
                        @if(Auth::user()->avatar == 'default_avatar.png')
                        <img src="/assets/images/default_avatar.png" width="70" class="img-rounded">
                        @else
                        <img src="/assets/avatars/{{ Auth::user()->hashed_id }}/{{ Auth::user()->avatar_normal }}"
                        width="70" class="img-rounded">
                        @endif
                        <a href="/profile/{{ Auth::user()->username }}">{{ Auth::user()->username }}</a>
                        <span class="user-type text-muted">
                            @if(Auth::user()->account_type == 1)
                            Teacher
                            @endif
                            @if(Auth::user()->account_type == 2)
                            Student
                            @endif
                        </span>
                        <span class="text-muted">{{ Auth::user()->forum_posts }} posts</span>
                    </div>
                    {{ Form::open(array('url' => 'the-forum/create-thread-reply', 'class' => 'reply-to-thread-form pull-left')) }}
                        <div class="form-group">
                            <textarea class="form-control" name="thread-reply"
                            class="form-control thread-reply" placeholder="Reply to this thread"></textarea>
                        </div>
                        <input type="hidden" name="thread-id" value="{{ $thread->forum_thread_id }}">
                        <button type="submit" class="btn btn-default">Reply</button>
                    {{ Form::close() }}
                    <div class="clearfix"></div>
                </li>
            </ul>
        </div>
    </div>
</div>

@stop

@section('js')
<script type="text/javascript" src="/assets/js/sitefunc/theforum.js"></script>
@stop
