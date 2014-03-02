@foreach($members as $key => $member)
@if($member->group_member_id != $ownerDetails->id)
<li class="member-details-holder" data-member-id="{{ $member->id }}"
data-group-id="{{ $member->group_id }}">
    <a href="/profile/{{ $member->username }}">
        {{ Helper::avatar(80, "normal", "pull-left", $member->id) }}
    </a>
    <div class="member-content-holder pull-right">
        @if(Auth::user()->account_type == 1 && $member->account_type == 2)
        <div class="dropdown pull-right">
            <a data-toggle="dropdown" href="#">More <i class="fa fa-chevron-down"></i></a>
            <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                <li><a href="#" class="show-change-password"
                data-user-id="{{ $member->group_member_id }}">Change Password</a></li>
                <li><a href="#">Remove from Group</a></li>
            </ul>
        </div>
        @endif

        <div class="member-name-text">
            <a href="/profile/{{ $member->username }}">{{ $member->name }}</a>
        </div>
        <div class="member-type text-muted">
            @if($member->account_type == 1)
            Teacher
            @endif
            @if($member->account_type == 2)
            Student
            @endif
        </div>
        <div class="member-username text-muted">{{ $member->username }}</div>


    </div>
    <div class="clearfix"></div>
</li>
@endif
@endforeach