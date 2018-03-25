{!! Form::open(['id' => 'edit-user-role-form', 'method' => 'post']) !!}

    <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="{{ trans('adminlte_lang::message.userrolename') }}" name="role_name" value="{{ $user_role_info->role_name }}" autofocus/>
        <span class="glyphicon glyphicon-menu-hamburger form-control-feedback"></span>
    </div>

    <div class="row padding-left10">
        <div class="col-sm-5">
            <strong>{{ trans('adminlte_lang::message.accesscontrol') }}:</strong>
            <div class="form-group">
                <div class="checkbox">
                    <label>
                    @if ($user_role_info->_create)
                    <input type="checkbox" name="_create" checked> {{ trans('adminlte_lang::message._create') }}
                    @else
                    <input type="checkbox" name="_create"> {{ trans('adminlte_lang::message._create') }}
                    @endif
                    </label>
                </div>

                <div class="checkbox">
                    <label>
                    @if ($user_role_info->_edit)
                    <input type="checkbox" name="_edit" checked> {{ trans('adminlte_lang::message._edit') }}
                    @else
                    <input type="checkbox" name="_edit"> {{ trans('adminlte_lang::message._edit') }}
                    @endif
                    </label>
                </div>

                <div class="checkbox">
                    <label>
                    @if ($user_role_info->_view)
                    <input type="checkbox" name="_view" checked> {{ trans('adminlte_lang::message._view') }}
                    @else
                    <input type="checkbox" name="_view"> {{ trans('adminlte_lang::message._view') }}
                    @endif
                    </label>
                </div>

                <div class="checkbox">
                    <label>
                    @if ($user_role_info->_delete)
                    <input type="checkbox" name="_delete" checked> {{ trans('adminlte_lang::message._delete') }}
                    @else
                    <input type="checkbox" name="_delete"> {{ trans('adminlte_lang::message._delete') }}
                    @endif
                    </label>
                </div>
            </div>
        </div>

        <div class="col-sm-5">
            <strong>{{ trans('adminlte_lang::message.notifications') }}:</strong>
            <div class="checkbox">
                <label>
                @if ($user_role_info->is_notify_email)
                <input type="checkbox" name="is_notify_email" checked> {{ trans('adminlte_lang::message.notifyemail') }}
                @else
                <input type="checkbox" name="is_notify_email"> {{ trans('adminlte_lang::message.notifyemail') }}
                @endif
                </label>
            </div>

            <strong>{{ trans('adminlte_lang::message.setisadmin') }}</strong>
            <div class="checkbox">
                <label>
                @if ($user_role_info->is_admin)
                <input type="checkbox" name="is_admin" checked> {{ trans('adminlte_lang::message.isadmin') }} 
                @else
                <input type="checkbox" name="is_admin"> {{ trans('adminlte_lang::message.isadmin') }}
                @endif
                </label>
            </div>
        </div>
    </div>

    {!! Form::hidden('id', $user_role_info->id, ['id' => 'id']) !!}

    {{ csrf_field() }}

{!! Form::close() !!}
