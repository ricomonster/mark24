<div class="modal-dialog modal-medium-size">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Load a previously created Quiz</h4>
        </div>
        <div class="modal-body">
            <table class="table">
                <tbody>
                    @if(empty($quizzes))
                    <tr>No quizzes found.</tr>
                    @endif
                    @if(!empty($quizzes))
                    @foreach($quizzes as $quiz)
                    <tr>
                        <a class="quiz-title quiz-to-load">{{ $quiz->title }}</a>
                        <div class="quiz-options">
                            <a href="/quiz-creator/{{ $quiz->quiz_id }}">Edit</a>
                            <span class="text-muted">|</span>
                            <a href="#">Delete Quiz</a>
                        </div>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>
        <div class="modal-footer"></div>
    </div>
</div>
