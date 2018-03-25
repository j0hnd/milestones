@if ($project_types)
    @foreach ($project_types as $i => $ptype)
    <tr id="row-{{ $ptype['id'] }}">
        <td class="text-center">{{ $i + 1 }}</td>
        <td>{{ $ptype['project_type_name'] }}</td>
        <td>
            <button type="button" id="toggle-edit-projecttype" class="btn btn-primary" data-id="{{ $ptype->id }}">{{ trans('adminlte_lang::message.edit') }}</button>
            <button type="button" id="toggle-delete-projecttype" class="btn btn-danger" data-id="{{ $ptype->id }}">{{ trans('adminlte_lang::message.delete') }}</button>
        </td>
    </tr>
    @endforeach
@else
    <tr>
        <td class="3">{{ trans('adminlte_lang::message.nouserrolefound') }}</td>
    </tr>
@endif
