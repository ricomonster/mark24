<div class="modal-dialog modal-medium-size">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Group Chat</h4>
        </div>
        <div class="modal-body" style="text-align: center;">
            <p>Are you sure you want to start a group chat?</p>

            <p>
                A prompt in the post stream will be shown to tell
                the group members that there is an ongoing group chat.
            </p>
        </div>
        <div class="modal-footer">
            <a href="#" class="cancel-delete-group" data-dismiss="modal">Cancel</a>
            <span class="text-muted">or</span>
            <button type="button" class="btn btn-primary" id="start_group_chat"
            data-group-id="{{ $group->group_id }}">Yes</button>
        </div>
    </div>
</div>
