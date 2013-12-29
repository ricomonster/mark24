<div class="manager-header">
    {{ Helper::avatar(42, "small", "img-rounded pull-left") }}
    <div class="taker-details pull-left">
        <h4>Username</h4>
        <div class="not-turned-in-message">Not turned in</div>
    </div>
    <div class="taker-score pull-right">
        <div class="no-score-set">
            {{ Form::open(array('class' => 'no-score-form')) }}
            <input type="text" class="form-control user-score">
            <span class="seperator">/</span>
            <input type="text" class="form-control total-score">
            <button type="submit" class="btn btn-primary submit-grade">Grade</button>
            {{ Form::close() }}
        </div>
        <div class="set-user-score"></div>
        <div class="user-total-score">
            <span class="user-score">5</span>
            <span class="seperator">/</span>
            <span class="total-score">5</span>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
<div class="no-submission">
    <span>This assignment has not been turned in via eLinet.</span>
</div>
<div class="assignment-response"><span>Yehey</span></div>
