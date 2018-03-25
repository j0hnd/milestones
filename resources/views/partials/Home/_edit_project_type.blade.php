{!! Form::open(['id' => 'edit-project-type-form', 'method' => 'post']) !!}

    <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="{{ trans('adminlte_lang::message.projecttypename') }}" name="project_type_name" value="{{ $project_type_info['project_type_name'] }}" autofocus/>
        <span class="glyphicon glyphicon-menu-hamburger form-control-feedback"></span>
    </div>

    {!! Form::hidden('id', $project_type_info['project_type_id'], ['id' => 'id']) !!}

    {{ csrf_field() }}

{!! Form::close() !!}
