<div class="modal-dialog modal-small-size">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Change Password</h4>
        </div>
        <div class="modal-body">
            {{ Form::open(array('url'=>'ajax/modal/submit-change-password', 'class'=>'change-password-modal')) }}
                <div class="form-group">
                    <div class="alert"></div>
                    <input type="password" name="reset-password" class="form-control" id="reset-password">
                </div>
                <input type="hidden" name="user-id" value="{{ $user->id }}">
            {{ Form::close() }}
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-primary" id="trigger_reset_password">Reset Password</button>
        </div>
    </div>
</div>
