@if (!is_null($projects))
    @foreach ($projects as $i => $project)
    <tr>
      <!-- <td class="padding-left15 text-center">{{ $i + 1 }}</td> -->
      <td class="padding-left25">{{ $project->project_name }}</td>
      <td>{{ $project->project_code }}</td>
      <td>{{ $project->project_type_name }}</td>
      <td>
          <button type="button" class="btn btn-primary toggle-view-project" data-pid="{{ $project->pid }}">{{ trans('adminlte_lang::message.projectinfo') }}</button>
          <button type="button" class="btn btn-primary toggle-milestones-project" data-pid="{{ $project->pid }}">{{ trans('adminlte_lang::message.milestones') }}</button>
      </td>
    </tr>
    @endforeach
@else
    <tr>
      <td colspan="4" class="text-center">{{ trans('adminlte_lang::message.projectnotfound') }}</td>
    </tr>
@endif
