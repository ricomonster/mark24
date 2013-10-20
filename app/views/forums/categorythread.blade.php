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

.forum-body .forum-thread-types { border: 0; padding: 0 20px; }

.forum-thread-stream { margin: 0; list-style: none; padding: 0; }
.forum-thread-stream .empty-thread { border-top: 1px solid #dfe4e8; padding: 20px; }

.forum-thread-stream .thread-holder { border-top: 1px solid #dfe4e8; }
.forum-thread-stream .thread-holder img { margin: 20px 0 0 20px; }
.forum-thread-stream .thread-holder .thread-details-holder {
    margin: 20px 0 20px 20px;
    width: 45%;
}

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

<div class="message-holder"><span></span></div>

<div class="modal fade" id="the_modal" tabindex="-1" role="dialog"
aria-labelledby="the_modal_label" aria-hidden="true"></div>

<div class="row the-forum">
    <div class="col-md-3">
        <a href="/the-forum/add-thread" class="btn btn-info btn-large btn-block post-thread-link">
            Post a Thread
        </a>

        <div class="forum-category-holder">
            <div class="title-holder">Forum Categories</div>
            <ul class="nav nav-pills nav-stacked">
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
                <h3>The Forum</h3>
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
                    @if($thread->avatar == 'default_avatar.png')
                    <img src="/assets/images/default_avatar.png" width="70" class="img-rounded pull-left">
                    @else
                    <img src="/assets/avatars/{{ $thread->hashed_id }}/{{ $thread->avatar_normal }}"
                    width="70" class="img-rounded pull-left">
                    @endif
                    <div class="thread-details-holder pull-left">
                        <div class="thread-title">
                            <h4>
                                <a href="/the-forum/thread/{{ $thread->seo_url }}/{{ $thread->forum_thread_id }}">
                                    {{ $thread->title }}
                                </a>
                            </h4>
                        </div>
                        <div class="thread-details">
                            By <a href="/profile/{{ $thread->username }}" class="thread-author">{{ $thread->username }}</a>,
                            <span class="thread-timestamp">{{ $timestamp }}</span> in
                            <a href="/the-forum/{{ $thread->seo_name }}" class="thread-category">{{ $thread->category_name }}</a>
                        </div>
                    </div>
                    <div class="thread-stats pull-right">
                        <span class="badge"><i class="icon-eye-open"></i> {{ $thread->views }}</span>
                        <span class="badge"><i class="icon-comment"></i> {{ $thread->replies }}</span>
                        @if(!empty($thread->last_reply_timestamp))
                        <?php $replyTimestamp = Helper::timestamp($thread->last_reply_timestamp); ?>
                        <span class="badge">
                            <i class="icon-share-alt"></i> {{ $replyTimestamp }}
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
