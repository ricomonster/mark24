@extends('templates.master')
@section('title')
Planner
@stop

@section('internalCss')
@stop

@section('content')
<div class="planner-wrapper">
    {{ date('F Y') }}
    {{ Helper::drawCalendar(date('m'), date('Y')) }}
</div>
@stop

@section('js')
<script>
(function($) {

})(jQuery)
</script>
@stop
