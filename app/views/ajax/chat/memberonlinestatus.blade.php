@foreach($members as $member)
@if(Auth::user()->id != $member->id)
<li><a href="#">
    {{ $member->name }}
    {{ Helper::checkUserOnline($member->online_timestamp) }}
</a></li>
@endif
@endforeach
