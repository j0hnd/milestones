@if ($team_members)
    @foreach ($team_members as $member)
    <tr id="row-{{ $member['pid'] }}">
        <td>{{ $member['name'] }}</td>
        <td>{{ $member['email'] }}</td>
        <td>{{ $member['role'] }}</td>
        <td>
            @if (session('_edit'))
            <button type="button" id="toggle-edit-member" class="btn btn-primary" data-id="{{ $member->uid }}">{{ trans('adminlte_lang::message.edit') }}</button>
            @endif

            @if (session('_delete'))
            <button type="button" id="toggle-delete-member" class="btn btn-danger" data-id="{{ $member->uid }}">{{ trans('adminlte_lang::message.delete') }}</button>
            @endif
        </td>
    </tr>
    @endforeach
@else
    <tr>
        <td class="4">{{ trans('adminlte_lang::message.noteammemberesfound') }}</td>
    </tr>
@endif
