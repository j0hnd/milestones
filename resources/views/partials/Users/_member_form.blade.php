{!! Form::open(['id' => 'member-form', 'method' => 'post']) !!}

    <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="{{ trans('adminlte_lang::message.fullname') }}" name="name" autofocus/>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
    </div>

    <div class="form-group has-feedback">
        <input type="email" class="form-control" placeholder="{{ trans('adminlte_lang::message.email') }}" name="email" />
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
    </div>

    <div class="form-group has-feedback">
        <select class="form-control" name="user_role_id">
            <option disabled selected>User Role</option>
            @if (count($user_roles))
              @foreach ($user_roles as $user_role)
                  @if ($user_role->role_name == "Project Manager")
              <option value="{{ $user_role->id }}" selected>{{ $user_role->role_name }}</option>
                    @else
              <option value="{{ $user_role->id }}">{{ $user_role->role_name }}</option>
                  @endif
              @endforeach
            @endif
        </select>
    </div>

{{ csrf_field() }}

{!! Form::close() !!}
