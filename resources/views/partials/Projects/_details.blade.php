<!-- project details -->
<div class="col-xs-12 padding-top17">
    <strong>{{ trans('adminlte_lang::message.projectdetails') }}</strong>

    <div class="form-group padding-top10">
        <label>{{ trans('adminlte_lang::message.projectname') }}</label>
        <input type="text" class="form-control form-changes project-name" id="project-name" name="project_name" placeholder="{{ trans('adminlte_lang::message.projectname') }}" maxlength="100">
        <small><span class="chars">100</span> characters remaining</small>
    </div>

    <div class="form-group">
        <label>{{ trans('adminlte_lang::message.projectcode') }}</label>
        <input type="text" class="form-control form-changes project-code" id="project-code" name="project_code" placeholder="{{ trans('adminlte_lang::message.projectcode') }}" maxlength="9">
    </div>

    <div class="form-group">
        <label>{{ trans('adminlte_lang::message.projectdescription') }}</label>
        <textarea name="description" class="form-control form-changes" rows="3"></textarea>
    </div>

    <div class="form-group">
        <label>
            <input type="checkbox" name="is_visible_dashboard"> {{ trans('adminlte_lang::message.visibleindashboard') }}
        </label>
    </div>
</div>

<div class="form-group col-md-9">
    <label>{{ trans('adminlte_lang::message.projecttype') }}</label>
    <select class="form-control form-changes" name="project_type_id">
        <option disabled value="">Select Project Type</option>
        @if (count($project_types))
            @foreach ($project_types as $project_type)
                <option value="{{ $project_type->id }}">{{ $project_type->project_type_name }}</option>
            @endforeach
        @endif
    </select>
</div>

<div class="col-md-9 padding-top10">
    <strong>{{ trans('adminlte_lang::message.team') }}</strong>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>{{ trans('adminlte_lang::message.headermembername') }}</th>
            <th>{{ trans('adminlte_lang::message.headermemberrole') }}</th>
            <th>&nbsp;</th>
        </tr>
        </thead>
        <tbody id="team-member-container">
        </tbody>
    </table>

    <button type="button" id="toggle-add-team-member" class="btn btn-default col-xs-6">{{ trans('adminlte_lang::message.addteammember') }}</button>
</div>
