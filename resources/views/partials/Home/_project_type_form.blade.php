{!! Form::open(['id' => 'project-type-form', 'method' => 'post']) !!}

    <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="{{ trans('adminlte_lang::message.projecttypename') }}" name="project_type_name" autofocus/>
        <span class="glyphicon glyphicon-menu-hamburger form-control-feedback"></span>
    </div>

{{ csrf_field() }}

{!! Form::close() !!}
