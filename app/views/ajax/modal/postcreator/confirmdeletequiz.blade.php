<div class="modal-dialog modal-small-size">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Delete Quiz</h4>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete this quiz?</p>
        </div>
        <div class="modal-footer">
            <a href="#" class="cancel-delete-group" data-dismiss="modal">Cancel</a>
            <span class="text-muted">or</span>
            <button type="button" class="btn btn-primary" id="trigger_delete_quiz"
            data-quiz-id="{{ $quizId }}">Yes</button>
        </div>
    </div>
</div>