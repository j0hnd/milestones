{!! Form::open(['id' => 'user-profile-form', 'method' => 'post']) !!}

    <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="{{ trans('adminlte_lang::message.fullname') }}" name="name" value="{{ $user_profile->name }}" autofocus/>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
    </div>

    <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="{{ trans('adminlte_lang::message.email') }}" name="email" value="{{ $user_profile->email }}" readonly/>
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
    </div>

    <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="{{ trans('adminlte_lang::message.password') }}" name="password"  autofocus/>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
    </div>

    <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="{{ trans('adminlte_lang::message.retypepassword') }}" name="password_confirmation"  autofocus/>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
    </div>

{{ csrf_field() }}

{!! Form::close() !!}
