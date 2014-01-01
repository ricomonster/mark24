<div class="modal-dialog modal-medium-size">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Report a Problem</h4>
        </div>
        <div class="modal-body report-problem-modal">
            <ul class="things-to-write">
                <li>What were you doing when your trouble occurred?</li>
                <li>What steps can we follow to reproduce the problem?</li>
            </ul>
            {{ Form::open(array('class' => 'report-problem-form')) }}
                <div class="form-group">
                    <textarea name="problem" class="form-control"
                    placeholder="Give a detailed description of the broken feature"></textarea>
                </div>
            {{ Form::close() }}
            <div class="tips">
                <p>Your account and device information will be attached to the report,
                and we will only respond if we require more information.</p>
            </div>
        </div>
        <div class="modal-footer">
            <a href="#" data-dismiss="modal">Cancel</a>
            <span class="text-muted">or</span>
            <button type="button" class="btn btn-primary" id="submit_problem">
                Yes
            </button>
        </div>
    </div>
</div>
