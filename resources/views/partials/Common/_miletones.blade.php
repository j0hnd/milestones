@if (date('Y') == date('Y', strtotime($date)))
    @php ($date = date('M. jS', strtotime($date)))
@else
    @php ($date = date('M. j, Y', strtotime($date)))
@endif

@if (date('Y') == date('Y', strtotime($completed_on)))
    @php ($completed_on = date('M. jS', strtotime($completed_on)))
@else
    @php ($completed_on = date('M. j, Y', strtotime($completed_on)))
@endif

@if ($milestone == 0 and is_numeric($milestone))
    @php ($milestone = 1)
@endif

@if ($milestone == 'completed')
    @php ($message = "Actual Completion Date: \n\r".$completed_on."\n\rMilestone Completion Date: \n\r".$date)
<div class="milestone-status-completed" data-toggle="tooltip" data-placement="top" data-original-title="{{ $message }}">
    <span class="completed"><i class="fa fa-check" aria-hidden="true"></i></span>
</div>
@elseif ($milestone >= 1 and $milestone <= 3)
<button type="button" class="milestone-near-due elo" data-toggle="popover" data-placement="top" data-original-title="{{ $title }}" data-content="Due: {{ $date }}<br/>Weeks remaining: {{ $milestone }}">
    {{ $milestone }}
</button>
@elseif ($milestone >= 4)
<button type="button" class="milestone-not-near-due elo" data-toggle="popover" data-placement="top" data-original-title="{{ $title }}" data-content="Due: {{ $date }}<br/>Weeks remaining: {{ $milestone }}">
    {{ $milestone }}
</button>
@elseif ($milestone < 0)
<button type="button" class="milestone-due elo" data-toggle="popover" data-placement="top" data-original-title="{{ $title }}" data-content="Due: {{ $date }}<br/>Weeks overdue: {{ $milestone * -1 }}">
    {{ $milestone }}
</button>
@else
<div class="milestone-status-no-progress">
    <span class="no-progress">N/A</span>
</div>
@endif
