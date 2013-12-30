@extends('templates.master')

@section('title')
Four Oh! Four - Page not found
@stop

@section('internalCss')
<style>
.four-oh-four-wrapper { margin: auto; width: 700px; }
.four-oh-four-wrapper .well { padding: 0; }
.four-oh-four-wrapper .page-header { font-size: 100px; margin: 0; padding: 15px 25px; }
.four-oh-four-wrapper .explanation { padding: 15px; }
.four-oh-four-wrapper .explanation h3 { margin: 10px 0; }
.four-oh-four-wrapper .navigations { padding: 15px; }
</style>
@stop

@section('content')
<div class="four-oh-four-wrapper">
    <div class="well">
        <h1 class="sad-face page-header">:(</h1>
        <div class="explanation">
            <h3>The page you requested was not found.</h3>
            <p>
                You may have clicked an expired link or mistyped the address.
                Some web addresses are case sensitive.
            </p>
        </div>
        <div class="navigations">
            <p><a href="/">Back to home</a></p>
            <p><a href="#">Report this as a problem</a></p>
        </div>
    </div>
</div>
@stop

@section('js')
@stop
