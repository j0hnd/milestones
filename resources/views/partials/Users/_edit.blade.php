{!! Form::open(['id' => 'member-form', 'method' => 'post']) !!}

    @if (count($user_info))
    <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="{{ trans('adminlte_lang::message.fullname') }}" name="name" value="{{ $user_info->name }}" autofocus/>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
    </div>

    <div class="form-group has-feedback">
        <input type="email" class="form-control" placeholder="{{ trans('adminlte_lang::message.email') }}" name="email" value="{{ $user_info->email }}" />
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
    </div>

    <div class="form-group has-feedback">
        <select class="form-control" name="user_role_id">
            <option disabled>User Role</option>
            @if (count($user_roles))
              @foreach ($user_roles as $user_role)
                @if ($user_role->id == $user_info->user_role_id)
                <option value="{{ $user_role->id }}" selected>{{ $user_role->role_name }}</option>
                @else
                <option value="{{ $user_role->id }}">{{ $user_role->role_name }}</option>
                @endif
              @endforeach
            @endif
        </select>
    </div>
    @endif

    <!-- {{ csrf_field() }} -->

    {!! Form::hidden('uid', $user_info->uid, ['id' => 'uid']) !!}

{!! Form::close() !!}
