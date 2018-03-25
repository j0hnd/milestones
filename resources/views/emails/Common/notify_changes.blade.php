<p>Hi there,</p>
<p>
There are changes made in the project {{ $project_name }} by one of your team members.<br>
List below are the changes:
</p>
<ul>
    @foreach ($changes as $change)
    <li>by {{ $change->name }} on {{ date('m/d/Y', strtotime($change->created_at)) }}, {{ $change->comment }}</li>
    @endforeach
</ul>
