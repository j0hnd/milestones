@if ($user_roles)
    @foreach ($user_roles as $i => $role)
    <tr id="row-{{ $role->id }}">
        <td class="text-center">{{ $i + 1 }}</td>
        <td>{{ $role->role_name }}</td>
        <td class="text-center">
            @if ($role->_create)
            <i class='fa fa-check' aria-hidden='true'></i>
            @endif
        </td>
        <td class="text-center">
            @if ($role->_edit)
            <i class='fa fa-check' aria-hidden='true'></i>
            @endif
        </td>
        <td class="text-center">
            @if ($role->_view)
            <i class='fa fa-check' aria-hidden='true'></i>
            @endif
        </td>
        <td class="text-center">
            @if ($role->_delete)
            <i class='fa fa-check' aria-hidden='true'></i>
            @endif
        </td>
        <td class="text-center">
            @if ($role->is_notify_email)
            <i class='fa fa-check' aria-hidden='true'></i>
            @endif
        </td>
        <td class="text-center">
            @if ($role->is_admin)
            <i class='fa fa-check' aria-hidden='true'></i>
            @endif
        </td>
        <td>
            <button type="button" id="toggle-edit-userrole" class="btn btn-primary" data-id="{{ $role->id }}">{{ trans('adminlte_lang::message.edit') }}</button>
            <button type="button" id="toggle-delete-userrole" class="btn btn-danger" data-id="{{ $role->id }}">{{ trans('adminlte_lang::message.delete') }}</button>
        </td>
    </tr>
    @endforeach
@else
    <tr>
        <td class="8">{{ trans('adminlte_lang::message.nouserrolefound') }}</td>
    </tr>
@endif
