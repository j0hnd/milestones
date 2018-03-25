@if (count($summary))
  @foreach ($summary as $i => $_summary)
    @php
      $color = ($i % 2) ? "odd" : "even";
    @endphp
  <tr id="row-{{ $_summary['pid'] }}" class="{{ $color }}">
      <!-- <td align="center">{{ $i + 1 }}</td> -->
      <td class="padding-left25">{{ $_summary['project_name'] }}</td>
      <td align="center">{{ $_summary['project_code'] }}</td>
      <td align="center">{{ $_summary['project_type_name'] }}</td>
      <td align="center">
          <div class="milestone-status-changes {{ $_summary['changes_count'] > 0 ? "toggle-comments" : "" }}" data-id="{{ $_summary['pid'] }}" data-toggle="tooltip" data-placement="top" data-original-title="Changes made on the project">
              <span class="no-progress">{{ $_summary['changes_count'] }}</span>
          </div>
      </td>
      <td align="center">
        @include('partials.Common._miletones', ['title' => 'Announcement', 'date' => $_summary['announcement_date'], 'completed_on' => $_summary['announcement_completed_at'], 'milestone' => $_summary['announcement']])
      </td>
      <td style="width:5%" align="center">
        @include('partials.Common._miletones', ['title' => 'Scoping & Design', 'date' => $_summary['scoping_design_date'], 'completed_on' => $_summary['scoping_design_completed_at'], 'milestone' => $_summary['scoping_design']])
      </td>
      <td align="center">
        @include('partials.Common._miletones', ['title' => 'Advertising', 'date' => $_summary['advertising_date'], 'completed_on' => $_summary['advertising_completed_at'], 'milestone' => $_summary['advertising']])
      </td>
      <td align="center">
        @include('partials.Common._miletones', ['title' => 'Award', 'date' => $_summary['award_date'], 'completed_on' => $_summary['award_completed_at'], 'milestone' => $_summary['award']])
      </td>
      <td align="center">
        @include('partials.Common._miletones', ['title' => 'Commencement', 'date' => $_summary['commencement_date'], 'completed_on' => $_summary['commencement_completed_at'], 'milestone' => $_summary['commencement']])
      </td>
      <td align="center">
        @include('partials.Common._miletones', ['title' => '20% Complete', 'date' => $_summary['20_percent_complete_date'], 'completed_on' => $_summary['20_percent_completed_at'], 'milestone' => $_summary['20_percent_complete']])
      </td>
      <td align="center">
        @include('partials.Common._miletones', ['title' => '40% Complete', 'date' => $_summary['40_percent_complete_date'], 'completed_on' => $_summary['40_percent_completed_at'], 'milestone' => $_summary['40_percent_complete']])
      </td>
      <td align="center">
        @include('partials.Common._miletones', ['title' => '60% Complete', 'date' => $_summary['60_percent_complete_date'], 'completed_on' => $_summary['60_percent_completed_at'], 'milestone' => $_summary['60_percent_complete']])
      </td>
      <td align="center">
        @include('partials.Common._miletones', ['title' => '80% Complete', 'date' => $_summary['80_percent_complete_date'], 'completed_on' => $_summary['80_percent_completed_at'], 'milestone' => $_summary['80_percent_complete']])
      </td>
      <td align="center">
        @include('partials.Common._miletones', ['title' => 'Practical Completion', 'date' => $_summary['practical_completion_date'], 'completed_on' => $_summary['practical_completion_completed_at'], 'milestone' => $_summary['practical_completion']])
      </td>
      <td>
          @if (!is_null($_summary['project_manager']))
              {{ $_summary['project_manager'] }}
          @else
              <i class="fa fa-minus" aria-hidden="true"></i>
          @endif
      </td>
  </tr>
  @if ($_summary['changes_count'] > 0)
  <tr id="comment-{{ $_summary['pid'] }}" class="hidden">
    <td class="" colspan="15">
      <div class="comment-wrapper">

      </div>
    </td>
  </tr>
  @endif
  @endforeach
@else
<tr>
  <td colspan="15" class="text-center">{{ trans('adminlte_lang::message.noprojectfound') }}</td>
</tr>
@endif
