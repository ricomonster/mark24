@extends('templates.master')

@section('title')
Quiz Manager
@stop

@section('internalCss')
<style>
.quiz-manager .manager-sidebar { padding: 5px 0; }
.quiz-manager .manager-sidebar .quiz-title-holder { border-bottom: 1px solid #dfe4e8; padding: 10px; }
.quiz-manager .manager-sidebar .quiz-title-holder .text-muted { display: block; }
.quiz-manager .manager-sidebar .show-type-holder { padding: 10px; }
.quiz-manager .manager-sidebar .show-type-holder .show-type { display: inline-block; width: 120px; }

.quiz-manager .main-panel { padding: 5px 0; }
.quiz-manager .main-panel .quiz-manager-default .default-header { border-bottom: 1px solid #dfe4e8; padding: 10px; }
.quiz-manager .main-panel .quiz-manager-default .quiz-assigned { background-color: #f3f5f7; border-bottom: 1px solid #dfe4e8; padding: 10px; }

.quiz-manager .main-panel .quiz-manager-proper .manager-header { border-bottom: 1px solid #dfe4e8; padding: 10px; }
.quiz-manager .main-panel .quiz-manager-proper .manager-header .taker-details { margin-left: 10px; }
.quiz-manager .main-panel .quiz-manager-proper .manager-header .taker-details h4 { margin: 0 0 5px; }
.quiz-manager .main-panel .quiz-manager-proper .quiz-item-pagination {  }
.quiz-manager .main-panel .quiz-manager-proper .quiz-item-pagination li a { border-radius: 0; border-top: 0; }
.quiz-manager .main-panel .quiz-manager-proper .quiz-item-pagination li:first-child a { border-left: 0; }

.question-item-holder .tab-pane { padding: 15px 20px; }

.question-responses {
    border-top: 1px solid #e3e3e3;
    margin-top: 20px;
    padding-top: 10px;
}

.question-responses .multiple-choice-response-holder {
    margin: 0;
    padding: 0;
    list-style: none;
}

.question-responses .multiple-choice-response-holder .option-wrapper .option-holder {
    background-color: #ccc;
    height: 60px;
    padding: 1px 1px 3px;
    margin-bottom: 10px;
}

.question-responses .multiple-choice-response-holder .option-wrapper .option-holder .choice-letter {
    display: inline-block;
    font-weight: bold;
    text-align: center;
    width: 25px;
}

.question-responses .multiple-choice-response-holder .option-wrapper .option-holder .choice-text {
    background-color: #ffffff;
    display: inline-block;
    height: 57px;
    padding: 5px 10px;
    width: 94.3%;
}

.question-responses .multiple-choice-response-holder .choice-answer .option-holder {
    background-color: #439724;
}
.question-responses .multiple-choice-response-holder .choice-answer .option-holder .choice-letter {
    color: #ffffff;
}
</style>
@stop
@section('content')
 <div class="quiz-manager">
    <div class="row">
        <div class="col-md-3">
            <div class="well manager-sidebar">
                <div class="quiz-title-holder">
                    <a href="#">{{ $quiz->title }}</a>
                    <span class="text-muted">Due Oct 20, 2013</span>
                </div>
                <div class="show-type-holder">
                    <span>Showing:</span>
                    <select class="show-type form-control">
                        <option value="all" selected>All</option>
                        <option value="ungraded">Ungraded</option>
                        <option value="graded">Graded</option>
                        <option value="not_turned_in">Not turned in</option>
                    </select>
                </div>
                <div class="student-lists"></div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="well main-panel">
                <div class="quiz-manager-default" style="display: none;">
                    <div class="default-header">
                        <div class="quiz-details">
                            <h4>{{ $quiz->title }}</h4>
                            <a href="#" class="text-muted">Quiz Overview</a>
                            <a href="#">All Submissions</a>
                        </div>
                    </div>
                    <div class="quiz-assigned">
                        <span>Assigned to:</span>
                        <a href="#">Group 1</a>, <a href="#">Group 2</a>
                    </div>
                    <div class="student-high-scores"></div>
                    <div class="question-breakdown"></div>
                </div>

                <div class="quiz-manager-proper">
                    <div class="manager-header">
                        <img src="/assets/defaults/avatar/default_avatar.png" width="42"
                        class="img-rounded pull-left">
                        <div class="taker-details pull-left">
                            <h4>Chester Benington</h4>
                            <div class="quiz-taken-details text-muted">
                                <span>Time Taken: 1:00:00:00</span>
                                <span>|</span>
                                <span>Turned in Oct 15, 2013 3:06 PM</span>
                            </div>
                            <div class="taken-controls">
                                <span>Graded</span>
                                <span>|</span>
                                <a href="#">Delete</a>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                    <ul class="nav nav-tabs quiz-item-pagination">
                        <li class="active"><a href="#1" data-toggle="tab">1</a></li>
                        <li><a href="#2" data-toggle="tab">2</a></li>
                        <li><a href="#3" data-toggle="tab">3</a></li>
                        <li><a href="#4" data-toggle="tab">4</a></li>
                    </ul>
                    <div class="tab-content question-item-holder">
                        <div class="tab-pane active" id="1">
                            <div class="question-point pull-right">Question Total: 1 points</div>
                            <div class="clearfix"></div>

                            <div class="question-text">
                                best nba team daw?
                            </div>

                            <div class="question-responses">
                                <ul class="multiple-choice-response-holder">
                                    <li class="clearfix option-wrapper">
                                        <div class="option-holder">
                                            <div class="choice-letter">A</div>
                                            <div class="choice-text">
                                                lakers
                                            </div>
                                        </div>
                                    </li>
                                    <li class="clearfix option-wrapper choice-answer">
                                        <div class="option-holder">
                                            <div class="choice-letter">B</div>
                                            <div class="choice-text">
                                                heat
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="tab-pane" id="2">
                            <div class="question-point pull-right">Question Total: 1 points</div>
                            <div class="clearfix"></div>

                            <div class="question-text">
                                best nba team daw?
                            </div>

                            <div class="question-responses">
                                <div class="response-true-false">
                                    <button class="btn btn-default true-false-answer
                                    ">
                                        TRUE
                                    </button>
                                    <button class="btn btn-default true-false-answer">
                                        FALSE
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="3">
                            <div class="question-point pull-right">Question Total: 1 points</div>
                            <div class="clearfix"></div>

                            <div class="question-text">
                                best nba team daw?
                            </div>
                            <div class="question-responses">
                                <div class="response-short-answer">
                                    asdf
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="4">...</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')

@stop
