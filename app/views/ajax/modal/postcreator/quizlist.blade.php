<div class="modal-dialog modal-medium-size">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Load a previously created Quiz</h4>
        </div>
        <div class="modal-body">
            <ul class="quiz-list">
                @if(empty($quizzes))
                <li>No quizzes found.</li>
                @endif
                @if(!empty($quizzes))
                @foreach($quizzes as $quiz)
                <li class="test">
                    <a href="#" class="quiz-title quiz-to-load"
                    data-quiz-id="{{ $quiz->quiz_id }}">{{ $quiz->title }}</a>
                    <div class="quiz-options">
                        <a href="/quiz-creator/{{ $quiz->quiz_id }}/edit">Edit</a>
                        <span class="text-muted">|</span>
                        <a href="#">Delete Quiz</a>
                    </div>
                </li>
                @endforeach
                @endif
            </ul>
        </div>
        <div class="modal-footer"></div>
    </div>
</div>
