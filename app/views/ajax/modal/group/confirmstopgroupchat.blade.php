<div class="modal-dialog modal-medium-size">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Stop Group Chat</h4>
        </div>
        <div class="modal-body" style="text-align: center;">
            <p>Are you sure you want to stop this group chat?</p>

            <p>
                All of the conversations will not be deleted and we will
                do our best to keep it in our archives so later on you
                can retrieve them.
            </p>
        </div>
        <div class="modal-footer">
            <a href="#" class="cancel-delete-group" data-dismiss="modal">Cancel</a>
            <span class="text-muted">or</span>
            <button type="button" class="btn btn-primary" id="stop_group_chat"
            data-group-id="{{ $conversation->conversation_id }}">Yes</button>
        </div>
    </div>
</div>
