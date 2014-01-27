@extends('templates.master')

@section('title')
The Forum - {{ $thread->title }}
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

.the-forum .add-forum-category { margin-bottom: 20px; }

.forum-thread-body { background-color: inherit; border: 0; margin: 0; padding: 0; }
.forum-thread-stream { margin: 0; list-style: none; padding: 0; }
.forum-thread-stream li { background-color: #ffffff; padding-bottom: 20px; }
.forum-thread-stream .thread-holder { border: 1px solid #dfe4e8; margin-bottom: 20px; }
.forum-thread-stream .thread-holder .author-details { margin: 20px 0 0 20px; text-align: center; width: 80px; }
.forum-thread-stream .thread-holder .author-details img { margin-bottom: 10px; }
.forum-thread-stream .thread-holder .author-details .user-type { display: block; }
.forum-thread-stream .thread-holder .thread-details-holder {
    margin: 20px 20px 20px 120px;
}

.forum-thread-stream .thread-holder .thread-details-holder .thread-holder-title {
    border-bottom: 1px solid #dfe4e8;
    font-size: 28px;
    margin-bottom: 20px;
    padding-bottom: 10px;
    word-wrap: break-word;
}

.forum-thread-stream .thread-holder .thread-details-holder .thread-holder-description {
    margin-top: 10px;
    word-wrap: break-word;
}

.forum-thread-stream .thread-reply-holder { border: 1px solid #dfe4e8; margin-bottom: 20px; }
.forum-thread-stream .thread-reply-holder .author-details { margin: 20px 0 0 20px; text-align: center; width: 80px; }
.forum-thread-stream .thread-reply-holder .author-details img { margin-bottom: 10px; }
.forum-thread-stream .thread-reply-holder .author-details .user-type { display: block; }
.forum-thread-stream .thread-reply-holder .thread-reply-details-holder {
    margin: 20px 20px 20px 120px;
}
.forum-thread-stream .thread-reply-holder .thread-reply-details-holder .thread-reply-description {
    margin-top: 10px;
    word-wrap: break-word;
}

.forum-thread-stream .reply-to-thread-holder { border: 1px solid #dfe4e8; margin-bottom: 20px; }
.forum-thread-stream .reply-to-thread-holder .author-details { margin: 20px 0 0 20px; text-align: center; width: 80px; }
.forum-thread-stream .reply-to-thread-holder .author-details img { margin-bottom: 10px; }
.forum-thread-stream .reply-to-thread-holder .author-details .user-type { display: block; }
.forum-thread-stream .reply-to-thread-holder .reply-to-thread-form {
    margin: 20px 20px 0 120px;
}

.forum-thread-stream .reply-to-thread-holder .reply-to-thread-form textarea {
    height: 200px;
    resize: none;
}

.forum-thread-stream .thread-pagination {
    background-color: inherit;
    margin: 0;
    padding: 0;
}

.forum-thread-stream .thread-pagination .pagination { margin: 10px 0 0; }

.forum-thread-stream .thread-pagination ul { margin: 0; list-style: none; padding: 0; }
.forum-thread-stream .thread-pagination ul li { background-color: inherit; display: inline-block; }
.forum-thread-stream .thread-pagination ul li a {
    background-color: #ffffff;
    border: 1px solid #dfe4e8;
    margin: 0 2px;
    padding: 10px 15px;
}

.forum-thread-stream .thread-pagination ul li a:hover { text-decoration: none; }
.forum-thread-stream .thread-pagination ul li span {
    background-color: #f0f0f0;
    border: 1px solid #dfe4e8;
    margin: 0 2px;
    padding: 10px 15px;
}
</style>
@stop

@section('content')
<div class="row the-forum">
    <div class="col-md-3">
        <a href="/the-forum/add-thread" class="btn btn-info btn-large btn-block post-thread-link">
            Post a Thread
        </a>

        <div class="thread-actions-holder">
            <div class="title-holder">Thread Actions</div>
            <ul class="nav nav-pills nav-stacked">
                @if(empty($followed))
                <li>
                    <a href="#" class="follow-thread"
                    data-thread-id="{{ $thread->forum_thread_id }}">Follow</a>
                </li>
                @else
                <li>
                    <a href="#" class="unfollow-thread"
                    data-thread-id="{{ $thread->forum_thread_id }}">Unfollow</a>
                </li>
                @endif
            </ul>
        </div>

        <div class="forum-category-holder">
            <div class="title-holder">Forum Categories</div>
            <ul class="nav nav-pills nav-stacked">
                <li><a href="/the-forum">Home</a></li>
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
                <li class="thread-pagination">
                    {{ $replies->links() }}
                </li>

                @if(empty($page) || $page == 1)
                <li class="thread-holder">
                    <?php $timestamp = Helper::timestamp($thread->thread_timestamp); ?>
                    <div class="author-details pull-left">
                        {{ Helper::avatar(70, "normal", "img-rounded", $thread->id) }}
                        <a href="/profile/{{ $thread->username }}">{{ $thread->username }}</a>
                        <span class="user-type text-muted">
                            @if($thread->account_type == 1)
                            Teacher
                            @endif
                            @if($thread->account_type == 2)
                            Student
                            @endif
                        </span>
                        <span class="text-muted">{{{ $thread->forum_posts }}} posts</span>
                    </div>
                    <div class="thread-details-holder">
                        @if($thread->user_id == Auth::user()->id)
                        <div class="thread-controls pull-right">
                            <a href="#" class="edit-thread">Edit Thread</a>
                        </div>
                        @endif
                        <div class="thread-holder-title">{{{ $thread->title }}}</div>
                        <span class="thread-holder-timestamp text-muted">{{ $timestamp }}</span>
                        <div class="thread-holder-description">
                            <?php echo nl2br($thread->description); ?>
                        </div>
                    </div>
                    {{ Form::open(array('url' => 'ajax/the-forum/update-thread', 'style' => 'display:none')) }}
                        <div class="form-group">
                            <input type="text" name="thread-title"
                            class="form-control thread-title">
                        </div>
                        <div class="form-group">
                            <textarea name="thread-description"
                            class="form-control thread-description"></textarea>
                        </div>
                    {{ Form::close() }}
                    <div class="clearfix"></div>
                </li>
                @endif

                @if(!$replies->isEmpty())
                <?php $items = count($replies); ?>
                <?php $i = 0; ?>
                @foreach($replies as $reply)
                <?php $timestamp = Helper::timestamp($reply->reply_timestamp); ?>
                <li class="thread-reply-holder" {{ (++$i === $items) ? 'id="last"' : null }}>
                    <div class="author-details pull-left">
                        {{ Helper::avatar(70, "normal", "img-rounded", $reply->id) }}
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
                    <div class="thread-reply-details-holder">
                        @if($reply->user_id == Auth::user()->id)
                        <div class="thread-controls pull-right">
                            <a href="#" class="edit-thread-reply">Edit Reply</a>
                        </div>
                        @endif
                        <span class="thread-reply-timestamp text-muted">{{ $timestamp }}</span>
                        <div class="thread-reply-description">
                            <?php echo nl2br(htmlentities($reply->reply)); ?>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                </li>
                @endforeach
                @endif

                <li class="thread-pagination">
                    {{ $replies->links() }}
                </li>

                <li class="reply-to-thread-holder">
                    <div class="author-details pull-left">
                        {{ Helper::avatar(70, "normal", "img-rounded") }}
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
                    {{ Form::open(array('url' => 'the-forum/create-thread-reply', 'class' => 'reply-to-thread-form')) }}
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
