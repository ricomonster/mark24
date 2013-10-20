@extends('templates.master')

@section('title')
The Forum
@stop

@section('internalCss')
<style>
.the-forum .post-topic-link { margin-bottom: 10px; }
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

.the-forum .forum-add-topic {}
.the-forum .forum-add-topic textarea { height: 200px; resize: none; }
</style>
@stop

@section('content')

<div class="message-holder"><span></span></div>

<div class="modal fade" id="the_modal" tabindex="-1" role="dialog"
aria-labelledby="the_modal_label" aria-hidden="true"></div>

<div class="row the-forum">
    <div class="col-md-3">
        <a href="/the-forum/add-topic" class="btn btn-info btn-large btn-block post-topic-link">
            Post a Topic
        </a>

        <div class="forum-category-holder">
            <div class="title-holder">Forum Categories</div>
            <ul class="nav nav-pills nav-stacked">
                @foreach($categories as $category)
                <li><a href="/the-forum/{{ $category->seo_name }}">{{ $category->name }}</a></li>
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
        <div class="well forum-add-topic">
            {{ Form::open(array('url' => 'the-forum/submit-new-topic')) }}
                <div class="form-group
                <?php echo (empty($errors->first('topic-title'))) ? null : 'has-error'; ?>">
                    @if(!empty($errors->first('topic-title')))
                    <span class="help-block">{{ $errors->first('topic-title') }}</span>
                    @endif
                    <input type="text" name="topic-title" class="form-control"
                    placeholder="Choose a topic title" value="{{ Input::old('topic-title') }}">
                </div>
                <div class="form-group
                <?php echo (empty($errors->first('topic-category'))) ? null : 'has-error'; ?>">
                    @if(!empty($errors->first('topic-category')))
                    <span class="help-block">{{ $errors->first('topic-category') }}</span>
                    @endif
                    <select name="topic-category" class="form-control">
                        <option value="" selected>-- Choose a category --</option>
                        @foreach($categories as $category)
                        <option value="{{ $category->forum_category_id }}"
                        <?php echo (Input::old('topic-category') == $category->forum_category_id) ? 'selected' : null; ?>>
                            {{ $category->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group
                <?php echo (empty($errors->first('topic-description'))) ? null : 'has-error'; ?>">
                    @if(!empty($errors->first('topic-description')))
                    <span class="help-block">{{ $errors->first('topic-description') }}</span>
                    @endif
                    <textarea name="topic-description" class="form-control"
                    placeholder="Write your first post here">{{ Input::old('topic-description') }}</textarea>
                </div>

                <button type="submit" class="btn btn-default">Post Topic</button>
            {{ Form::close() }}
        </div>
    </div>
</div>

@stop

@section('js')
<script type="text/javascript" src="/assets/js/sitefunc/theforum.js"></script>
@stop
