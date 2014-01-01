<div class="modal-dialog modal-small-size">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Withdraw</h4>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to withdraw from {{ $group->group_name }}?</p>
        </div>
        <div class="modal-footer">
            <a href="#" class="btn btn-default" data-dismiss="modal">Cancel</a>
            <span class="text-muted">or</span>
            <button type="button" class="btn btn-primary" id="trigger_withdraw_group"
            data-group-id="{{ $group->group_id }}">Yes</button>
        </div>
    </div>
</div>
