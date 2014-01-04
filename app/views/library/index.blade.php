@extends('templates.master')
@section('title')
The Library
@stop

@section('internalCss')
<link href="/assets/css/site/library.style.css" rel="stylesheet">
@stop

@section('content')
<div class="the-library-wrapper">
    <div class="row">
        <div class="col-md-3">
            <ul class="library-items nav nav-pills nav-stacked">
                <li class="active"><a href="/the-library">
                    <i class="fa fa-table"></i>Library Items
                </a></li>
                <li><a href="/the-library/attached">
                    <i class="fa fa-link"></i>Attached to Posts
                </a></li>
            </ul>
        </div>
        <div class="col-md-9">
            <div class="well library-items-wrapper">
                <div class="page-header">
                    <div class="pull-right library-controls">
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                View
                                <i class="fa fa-angle-down"></i>
                            </a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu1">
                                <li class="active" role="presentation"><a role="menuitem" tabindex="-1"
                                href="#" class="library-view" data-view="thumb">Thumb</a></li>
                                <li role="presentation"><a role="menuitem" tabindex="-1"
                                href="#" class="library-view" data-view="list">List</a></li>
                            </ul>
                        </div>
                    </div>
                    <h3 class="pull-left">Attached to Posts</h3>
                    <div class="clearfix"></div>
                </div>
                <div class="library-items-content">
                    <ul class="file-stream thumbnail-view clearfix">
                        @foreach($files as $file)
                        <li class="file-holder">
                            <div class="thumbnail">
                                @if($file->file_thumbnail == 'file.png' || $file->file_thumbnail == 'zip.png')
                                <img src="/assets/defaults/icons/{{ $file->file_thumnail }}">
                                @endif
                                @if($file->file_thumbnail != 'file.png' || $file->file_thumbnail != 'zip.png')
                                <img src="/assets/thelibrary/{{ $file->file_thumbnail }}" class="image">
                                @endif
                            </div>
                            <p class="file-name">
                                {{ (strlen($file->file_name) > 53) ?
                                substr($file->file_name, 0, 50).'...' : $file->file_name; }}
                            </p>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="/assets/js/sitefunc/library.js"></script>
@stop
