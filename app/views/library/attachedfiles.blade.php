@extends('templates.master')
@section('title')
The Library
@stop

@section('internalCss')
<style>
.the-library-wrapper .library-items li { margin: 0; }
.the-library-wrapper .library-items li a {
    background-color: #ffffff;
    border: 1px solid #dfe4e8 !important;
    border-top: 0 !important;
    border-radius: 0;
}

.the-library-wrapper .library-items li.active a { background-color: #f3f5f7; color: #2a6496; }
.the-library-wrapper .library-items li a i { color: #8890a2; font-size: 18px; margin-right: 10px; }
.the-library-wrapper .well { padding: 0; }
.the-library-wrapper .page-header { margin: 0; padding: 20px; }
.the-library-wrapper .page-header h3 { margin: 0; }
</style>
@stop

@section('content')
<div class="message-holder"><span></span></div>
<div class="the-library-wrapper">
    <div class="row">
        <div class="col-md-3">
            <ul class="library-items nav nav-pills nav-stacked">
                <li><a href="/the-library">
                    <i class="fa fa-table"></i>Library Items
                </a></li>
                <li><a href="/the-library/folders">
                    <i class="fa fa-folder-o"></i>Folders
                </a></li>
                <li class="active"><a href="/the-library/attached">
                    <i class="fa fa-link"></i>Attached to Posts
                </a></li>
            </ul>
        </div>
        <div class="col-md-9">
            <div class="well library-items-wrapper">
                <div class="page-header">
                    <h3>Attached to Posts</h3>
                </div>
                <div class="library-items-content">
                    <ul class="file-stream">
                        @foreach($files as $file)
                        <li class="file-holder">

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
<script>
(function($) {

})(jQuery)
</script>
@stop
