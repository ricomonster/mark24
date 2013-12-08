<div class="modal-dialog modal-medium-size">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Delete message</h4>
        </div>
        <div class="modal-body delete-post-modal-message">
            <p>Are you sure you want to delete this message?</p>
            <p>
                Deleting a quiz is not recommended and will remove all work and
                grades for student submissions. This action cannot be undone.
            </p>
        </div>
        <div class="modal-footer">
            <a href="#" class="cancel-delete-post" data-dismiss="modal">Cancel</a>
            <span class="text-muted">or</span>
            <button type="button" class="btn btn-primary" id="trigger_delete_post"
            data-post-id="{{ $post->post_id }}">Ok</button>
        </div>
    </div>
</div>
