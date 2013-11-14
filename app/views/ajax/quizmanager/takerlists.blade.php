@foreach($takers as $taker)
<li class="group-name"><a href="#">{{ $taker['group_name'] }}</a></li>
@if(isset($taker['members']))
@foreach($taker['members'] as $member)
<li>
    <a href="#" class="show-taker-details"
    data-user-id="{{ $member['id'] }}">
        {{ $member['firstname'].' '.$member['lastname'] }}
    </a>
</li>
@endforeach
@endif
@endforeach
