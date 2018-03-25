<div class="row padding-10">
    <form id="update-projects" method="post">

        <!-- project details -->
        <div class="col-xs-12 padding-top17">
            <strong>{{ trans('adminlte_lang::message.editprojectdetails') }}</strong>

            <div class="form-group padding-top10">
                <label>{{ trans('adminlte_lang::message.projectname') }}</label>
                <input type="text" class="form-control form-changes project-name" id="project-name" data-index="details" data-field="project_name" data-original="{{ $project_details['project_name'] }}" name="project_name" value="{{ $project_details['project_name'] }}" placeholder="{{ trans('adminlte_lang::message.projectname') }}" maxlength="100">
                <small><span class="chars">100</span> characters remaining</small>
                {!! Form::hidden('pid', $project_details['pid'], ['id' => 'pid']) !!}
            </div>

            <div class="form-group">
                <label>{{ trans('adminlte_lang::message.projectcode') }}</label>
                <input type="text" class="form-control form-changes project-code" id="project-code" data-index="details" data-field="project_code" data-original="{{ $project_details['project_code'] }}" name="project_code" value="{{ $project_details['project_code'] }}" placeholder="{{ trans('adminlte_lang::message.projectcode') }}" maxlength="9">
            </div>

            <div class="form-group">
                <label>{{ trans('adminlte_lang::message.projectdescription') }}</label>
                <textarea name="description" class="form-control form-changes" data-index="details" data-field="description" data-original="{{ $project_details['description'] }}" rows="3">{{ $project_details['description'] }}</textarea>
            </div>

            <div class="checkbox">
                <label>
                    @if ($project_details['is_visible_dashboard'])
                    <input type="checkbox" class="form-changes" name="is_visible_dashboard" data-index="details" data-field="is_visible_dashboard" data-original="{{ $project_details['is_visible_dashboard'] }}" checked>
                    @else
                    <input type="checkbox" class="form-changes" name="is_visible_dashboard" data-index="details" data-field="is_visible_dashboard" data-original="{{ $project_details['is_visible_dashboard'] }}">
                    @endif

                    {{ trans('adminlte_lang::message.visibleindashboard') }}
                </label>
            </div>
        </div>

        <div class="form-group col-md-9">
            <label>{{ trans('adminlte_lang::message.projecttype') }}</label>
            <select class="form-control form-changes" name="project_type_id" data-index="details" data-field="project_type_id" data-original="{{ $project_details['project_type']['project_type_id'] }}">
                <option disabled value="">Select Project Type</option>
                @if (count($project_types))
                    @foreach ($project_types as $project_type)
                        @if ($project_type->id == $project_details['project_type']['project_type_id'])
                            <option value="{{ $project_type->id }}" selected>{{ $project_type->project_type_name }}</option>
                        @else
                            <option value="{{ $project_type->id }}">{{ $project_type->project_type_name }}</option>
                        @endif
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
                @include('partials.Users._row_selected_member', ['user_info' => $project_details['members']])
                </tbody>
            </table>

            <button type="button" id="toggle-add-team-member" class="btn btn-default col-xs-6">{{ trans('adminlte_lang::message.addteammember') }}</button>
        </div>

        {{ csrf_field() }}
    </form>
</div>
<div class="row padding-10 margin-top30">
    <div class="col-md-12 text-right">
        <hr style="margin-top:12px; margin-bottom:12px;">
        <button type="button" id="" class="btn btn-link" data-dismiss="modal">{{ trans('adminlte_lang::message.close') }}</button>
        <button type="button" id="toggle-update-project" data-id="{{ $project_details['pid'] }}" class="btn btn-primary">{{ trans('adminlte_lang::message.updateproject') }}</button>
    </div>
</div>
