@extends('templates.master')

@section('title')
The Forum
@stop

@section('internalCss')
<style>
.the-forum .forum-body { /*background-color: transparent;*/ margin: 0; padding: 0; }
.the-forum .forum-body .forum-title {
    background-color: #fff;
    padding: 10px 20px;
}

.the-forum .post-thread-link { margin-bottom: 10px; }
.the-forum .forum-category-holder { margin-bottom: 10px; }
.the-forum .forum-category-holder .title-holder {
    background-color: #757f93;
    color: #ffffff;
    padding: 12px;
}

.the-forum .forum-category-holder ul li { margin: 0; }
.the-forum .forum-category-holder ul li.active a {
    background-color: #f3f5f7;
    color: #2a6496;
}

.the-forum .forum-category-holder ul li a {
    background-color: #ffffff;
    border: 1px solid #dfe4e8 !important;
    border-top: 0 !important;
    border-radius: 0;
}

.the-forum .add-forum-category { margin-bottom: 20px; }

.forum-body .forum-thread-types { border: 0; padding: 0 20px; }

.forum-thread-stream { margin: 0; list-style: none; padding: 0; }
.forum-thread-stream .empty-thread { border-top: 1px solid #dfe4e8; padding: 20px; }

.forum-thread-stream .thread-holder { border-top: 1px solid #dfe4e8; }
.forum-thread-stream .thread-holder img { margin: 20px 0 20px 20px; }
.forum-thread-stream .thread-holder .thread-details-holder {
    margin: 20px 0 20px 20px;
    width: 45%;
}

.forum-thread-stream .thread-holder .thread-details-holder .thread-title { font-size: 20px; }
.forum-thread-stream .thread-holder .thread-details-holder .thread-details { color: #999999; }
.forum-thread-stream .thread-holder .thread-stats {
    font-size: 16px;
    margin: 20px 20px 20px 0;
    text-align: right;
    width: 35%;
}
</style>
@stop

@section('content')
<div class="row the-forum">
    <div class="col-md-3">
        <a href="/the-forum/add-thread" class="btn btn-info btn-large btn-block post-thread-link">
            Post a Thread
        </a>

        <div class="forum-category-holder">
            <div class="title-holder">Forum Categories</div>
            <ul class="nav nav-pills nav-stacked">
                <li><a href="/the-forum">Home</a></li>
                @foreach($categories as $category)
                <li class="<?php echo ($categoryDetails->forum_category_id == $category->forum_category_id) ? 'active' : null; ?>">
                    <a href="/the-forum/{{ $category->seo_name }}">{{ $category->category_name }}</a>
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
        <div class="well forum-body">
            <div class="forum-title">
                <h3>The Forum: {{ $categoryDetails->category_name }}</h3>
                <div class="text-muted"><?php echo nl2br($categoryDetails->description); ?></div>
            </div>

            <ul class="nav nav-tabs forum-thread-types">
                <li class="<?php echo (empty($sort) || $sort == 'latest') ? 'active' : null; ?>">
                    <a href="?sort=latest">Latest</a>
                </li>
                <li class="<?php echo (!empty($sort) && $sort == 'popular') ? 'active' : null; ?>">
                    <a href="?sort=popular">Popular</a>
                </li>
                <li class="<?php echo (!empty($sort) && $sort == 'unanswered') ? 'active' : null; ?>">
                    <a href="?sort=unanswered">Unanswered</a>
                </li>
                <li class="<?php echo (!empty($sort) && $sort == 'following') ? 'active' : null; ?>">
                    <a href="?sort=following">Following</a>
                </li>
                <li class="<?php echo (!empty($sort) && $sort == 'my-topics') ? 'active' : null; ?>">
                    <a href="?sort=my-topics">My Topics</a>
                </li>
                <li class="<?php echo (!empty($sort) && $sort == 'last-viewed') ? 'active' : null; ?>">
                    <a href="?sort=last-viewed">Last Viewed</a>
                </li>
            </ul>

            <ul class="forum-thread-stream">
                @if($threads->isEmpty())
                <li class="empty-thread">
                    Ooops, there are no threads found..
                </li>
                @endif
                @if(!$threads->isEmpty())
                @foreach($threads as $thread)
                <?php $timestamp = Helper::timestamp($thread->timestamp); ?>
                <li class="thread-holder">
                    {{ Helper::avatar(70, "normal", "img-rounded pull-left", $thread->id) }}
                    <div class="thread-details-holder pull-left">
                        <div class="thread-title">
                            <a href="/the-forum/thread/{{ $thread->seo_url }}/{{ $thread->forum_thread_id }}">
                                @if((empty($sort) || $sort == 'latest') && $thread->sticky_post == 'TRUE')
                                    <span class="sticky-post text-muted">[Sticky]</span>
                                    @endif
                                    {{ $thread->title }}
                            </a>
                        </div>
                        <div class="thread-details">
                            By <a href="/profile/{{ $thread->username }}" class="thread-author">{{ $thread->username }}</a>,
                            <span class="thread-timestamp">{{ $timestamp }}</span> in
                            <a href="/the-forum/{{ $thread->seo_name }}" class="thread-category">{{ $thread->category_name }}</a>
                        </div>
                    </div>
                    <div class="thread-stats pull-right">
                        <span class="badge"><i class="fa fa-eye"></i> {{ $thread->views }}</span>
                        <span class="badge"><i class="fa fa-comment"></i> {{ $thread->replies }}</span>
                        @if(!empty($thread->last_reply_timestamp))
                        <?php $replyTimestamp = Helper::timestamp($thread->last_reply_timestamp); ?>
                        <span class="badge">
                            <i class="fa fa-share"></i> {{ $replyTimestamp }}
                        </span>
                        @endif
                    </div>
                    <div class="clearfix"></div>
                </li>
                @endforeach
                @endif
            </ul>
        </div>
    </div>
</div>

@stop

@section('js')
<script type="text/javascript" src="/assets/js/sitefunc/theforum.js"></script>
@stop
