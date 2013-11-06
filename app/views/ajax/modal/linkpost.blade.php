<div class="modal-dialog modal-small-size">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Link to this Post</h4>
        </div>
        <div class="modal-body">
            <input type="text" class="form-control"
            value="{{ Request::root().'/post/'.$post->post_id }}">
        </div>
        <div class="modal-footer">
            <a href="#" data-dismiss="modal">Cancel</a>
            <span class="text-muted">or</span>
            <a href="/post/{{ $post->post_id }}" class="btn btn-primary">View Message</a>
        </div>
    </div>
</div>
