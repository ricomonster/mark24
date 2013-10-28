<div class="modal-dialog modal-medium-size">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Delete Group</h4>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete this group?</p>

            <p>
                Deleting a group removes all members from this group.
                All messages posted to this group will be deleted.
                This action cannot be undone.
            </p>
        </div>
        <div class="modal-footer">
            <a href="#" class="cancel-delete-group" data-dismiss="modal">Cancel</a>
            <span class="text-muted">or</span>
            <button type="button" class="btn btn-primary" id="trigger_delete-group"
            data-group-id="{{ $group->group_id }}">Yes</button>
        </div>
    </div>
</div>
