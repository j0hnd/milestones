{!! Form::open(['id' => 'user-role-form', 'method' => 'post']) !!}

    <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="{{ trans('adminlte_lang::message.userrolename') }}" name="role_name" autofocus/>
        <span class="glyphicon glyphicon-menu-hamburger form-control-feedback"></span>
    </div>

    <div class="row padding-left10">
        <div class="col-sm-5">
            <strong>{{ trans('adminlte_lang::message.accesscontrol') }}:</strong>
            <div class="form-group">
                <div class="checkbox">
                    <label> <input type="checkbox" name="_create"> {{ trans('adminlte_lang::message._create') }} </label>
                </div>

                <div class="checkbox">
                    <label> <input type="checkbox" name="_edit"> {{ trans('adminlte_lang::message._edit') }} </label>
                </div>

                <div class="checkbox">
                    <label> <input type="checkbox" name="_view"> {{ trans('adminlte_lang::message._view') }} </label>
                </div>

                <div class="checkbox">
                    <label> <input type="checkbox" name="_delete"> {{ trans('adminlte_lang::message._delete') }} </label>
                </div>
            </div>
        </div>

        <div class="col-sm-5">
            <strong>{{ trans('adminlte_lang::message.notifications') }}:</strong>
            <div class="checkbox">
                <label> <input type="checkbox" name="is_notify_email"> {{ trans('adminlte_lang::message.notifyemail') }} </label>
            </div>

            <strong>{{ trans('adminlte_lang::message.setisadmin') }}</strong>
            <div class="checkbox">
                <label> <input type="checkbox" name="is_admin"> {{ trans('adminlte_lang::message.isadmin') }} </label>
            </div>
        </div>
    </div>

    {{ csrf_field() }}

{!! Form::close() !!}
