<ul class="user-stats">
    @if($user->account_type == 1)
    <li>
        <h3 class="stat-number">{{ $details->student_count }}</h3>
        <p class="stat-name">Students</p>
    </li>
    <li>
        <h3 class="stat-number">{{ $details->group_count }}</h3>
        <p class="stat-name">Groups</p>
    </li>
    <li>
        <h3 class="stat-number">{{ $details->file_count }}</h3>
        <p class="stat-name">Library Items</p>
    </li>
    @endif
    @if($user->account_type == 2)
    <li>
        <h3 class="stat-number">{{ $details->post_replies }}</h3>
        <p class="stat-name">Post & Replies</p>
    </li>
    <li>
        <h3 class="stat-number">{{ $details->group_count }}</h3>
        <p class="stat-name">Groups</p>
    </li>
    @endif
    <li>
        <h3 class="stat-number">{{ $user->forum_posts }}</h3>
        <p class="stat-name">Forum Post & Replies</p>
    </li>
</ul>
