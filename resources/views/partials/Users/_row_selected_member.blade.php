@if (count($user_info))
    @foreach ($user_info as $info)
    <tr id="row-{{ $info->uid }}">
        <td>
            {{ $info->name }}
            {!! Form::hidden('hidden', $info->uid, ['name' => 'members[uid][]']) !!}
        </td>
        <td>
            {{ $info->role_name }}
        </td>
        <td class="text-center">
            @if (isset($info->is_owner))
                @if ($info->is_owner == 1)
                    <strong>{{ trans('adminlte_lang::message.owner') }}</strong>
                    {!! Form::hidden('is_owner', 1, ['name' => 'members[is_owner][]']) !!}
                @else
                    <a href="#" class="toggle-remove-member" data-id="{{ $info->uid }}">
                        <i class="fa fa-minus-circle" aria-hidden="true"></i>
                    </a>
                    {!! Form::hidden('is_owner', 0, ['name' => 'members[is_owner][]']) !!}
                @endif
            @else
                <a href="#" class="toggle-remove-member" data-id="{{ $info->uid }}">
                    <i class="fa fa-minus-circle" aria-hidden="true"></i>
                </a>
                {!! Form::hidden('is_owner', 0, ['name' => 'members[is_owner][]']) !!}
            @endif
        </td>
    </tr>
    @endforeach
@endif
