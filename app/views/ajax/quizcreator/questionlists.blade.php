@foreach($lists as $key => $list)
<li class="<?php echo ($key == 0) ? 'active' : null; ?>">
    <a href="#" class="question-list-item" data-question-list-id="{{ $list->question_list_id }}"
    data-question-id="{{ $list->question_id }}">{{ $key + 1 }}</a>
</li>
@endforeach
