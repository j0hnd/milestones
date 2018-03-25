<div class="tab-content clearfix">
    <div class="tab-pane active" id="details">
        <div class="row padding-10">
            <!-- project details -->
            <div class="col-xs-12 padding-top17">
                <strong>{{ trans('adminlte_lang::message.editprojectdetails') }}</strong>

                <div class="form-group padding-top10">
                    <label>{{ trans('adminlte_lang::message.projectname') }}</label>
                    <input type="text" class="form-control" name="project_name" value="{{ $project_details['project_name'] }}" disabled>
                </div>

                <div class="form-group">
                    <label>{{ trans('adminlte_lang::message.projectcode') }}</label>
                    <input type="text" class="form-control" name="project_code" value="{{ $project_details['project_code'] }}" disabled>
                </div>

                <div class="form-group">
                    <label>{{ trans('adminlte_lang::message.projectdescription') }}</label>
                    <textarea name="description" class="form-control" rows="3" disabled>{{ $project_details['description'] }}</textarea>
                </div>

                <div class="form-group">
                    <label>
                        @if ($project_details['is_visible_dashboard'])
                        <input type="checkbox" name="is_visible_dashboard" disabled checked>
                        @else
                        <input type="checkbox" name="is_visible_dashboard" disabled>
                        @endif

                        {{ trans('adminlte_lang::message.visibleindashboard') }}
                    </label>
                </div>
            </div>

            <div class="form-group col-md-9">
                <label>{{ trans('adminlte_lang::message.projecttype') }}</label>
                <select class="form-control" name="project_type_id"  disabled>
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
            </div>
        </div>

        <div class="row padding-10 margin-top30">
            <div class="col-md-12 text-right">
                <hr style="margin-top:12px; margin-bottom:12px;">
                <button type="button" id="" class="btn btn-link" data-dismiss="modal">{{ trans('adminlte_lang::message.close') }}</button>
                @if (session('_edit'))
                <button type="button" id="toggle-edit-project" data-id="{{ $project_details['pid'] }}" class="btn btn-primary">{{ trans('adminlte_lang::message.editproject') }}</button>
                @endif
                @if (session('_delete'))
                <button type="button" data-id="{{ $project_details['pid'] }}" class="btn btn-danger toggle-delete-project">{{ trans('adminlte_lang::message.delete') }}</button>
                @endif
            </div>
        </div>
    </div>

    <style>
        #update-milestones input[type="text"] {
            height: 35px !important;
        }
    </style>

    {{-- milestones --}}
    <div class="tab-pane" id="milestones">
        <div class="row">
            <div class="col-md-12 padding-top17">
                {{-- announcement --}}
                <div class="row">
                    <div class="col-md-12 margin-left15">
                        <div class="col-md-3">
                            <label> {{ trans('adminlte_lang::message.announcement') }}</label>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group" style="width: 100%">
                                <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" name="announcement"value="{{ $project_details['milestones']['announcement'] }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-4 padding-top5">
                            <input type="checkbox" id="announcement" name="is_announcement" {{ $project_details['milestones']['is_announcement'] == 1 ? "checked" : "" }} disabled>
                            <label for="announcement"> {{ trans('adminlte_lang::message.markcomplete') }}</label>
                        </div>
                    </div>
                </div>

                {{-- scoping and design --}}
                <div class="row margin-top10">
                    <div class="col-md-12 margin-left15">
                        <div class="col-md-3">
                            <label>{{ trans('adminlte_lang::message.scoping') }}</label>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group" style="width: 100%">
                                <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" name="scoping_design" value="{{ $project_details['milestones']['scoping_design'] }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-4 padding-top5">
                            <input type="checkbox" id="scoping_design" name="is_scoping_design" {{ $project_details['milestones']['is_scoping_design'] == 1 ? "checked" : "" }} disabled>
                            <label for="scoping_design"> {{ trans('adminlte_lang::message.markcomplete') }}</label>
                        </div>
                    </div>
                </div>

                {{-- advertising --}}
                <div class="row margin-top10">
                    <div class="col-md-12 margin-left15">
                        <div class="col-md-3">
                            <label> {{ trans('adminlte_lang::message.advertising') }}</label>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group" style="width: 100%">
                                <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" name="advertising" value="{{ $project_details['milestones']['advertising'] }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-4 padding-top5">
                            <input type="checkbox" id="advertising" name="is_advertising" {{ $project_details['milestones']['is_advertising'] == 1 ? "checked" : "" }} disabled>
                            <label for="advertising"> {{ trans('adminlte_lang::message.markcomplete') }}</label>
                        </div>
                    </div>
                </div>

                {{-- award --}}
                <div class="row margin-top10">
                    <div class="col-md-12 margin-left15">
                        <div class="col-md-3">
                            <label> {{ trans('adminlte_lang::message.award') }}</label>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group" style="width: 100%">
                                <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" name="award" value="{{ $project_details['milestones']['award'] }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-4 padding-top5">
                            <input type="checkbox" id="award" name="is_award" {{ $project_details['milestones']['is_award'] == 1 ? "checked" : "" }} disabled>
                            <label for="award"> {{ trans('adminlte_lang::message.markcomplete') }}</label>
                        </div>
                    </div>
                </div>

                {{-- commencement --}}
                <div class="row margin-top10">
                    <div class="col-md-12 margin-left15">
                        <div class="col-md-3">
                            <label> {{ trans('adminlte_lang::message.commencement') }}</label>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group" style="width: 100%">
                                <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" name="commencement" value="{{ $project_details['milestones']['commencement'] }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-4 padding-top5">
                            <input type="checkbox" id="commencement" name="is_commencement" {{ $project_details['milestones']['is_commencement'] == 1 ? "checked" : "" }} disabled>
                            <label for="commencement"> {{ trans('adminlte_lang::message.markcomplete') }}</label>
                        </div>
                    </div>
                </div>

                {{-- 20% complete --}}
                <div class="row margin-top10">
                    <div class="col-md-12 margin-left15">
                        <div class="col-md-3">
                            <label> {{ trans('adminlte_lang::message.20percent') }}</label>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group" style="width: 100%">
                                <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" name="20_percent_complete" value="{{ $project_details['milestones']['20_percent_complete'] }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-4 padding-top5">
                            <input type="checkbox" id="20_percent_complete" name="is_20_percent_complete" {{ $project_details['milestones']['is_20_percent_complete'] == 1 ? "checked" : "" }} disabled>
                            <label for="20_percent_complete"> {{ trans('adminlte_lang::message.markcomplete') }}</label>
                        </div>
                    </div>
                </div>

                {{-- 40% complete --}}
                <div class="row margin-top10">
                    <div class="col-md-12 margin-left15">
                        <div class="col-md-3">
                            <label> {{ trans('adminlte_lang::message.40percent') }}</label>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group" style="width: 100%">
                                <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" name="40_percent_complete" value="{{ $project_details['milestones']['40_percent_complete'] }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-4 padding-top5">
                            <input type="checkbox" id="40_percent_complete" name="is_40_percent_complete" {{ $project_details['milestones']['is_40_percent_complete'] == 1 ? "checked" : "" }} disabled>
                            <label for="40_percent_complete"> {{ trans('adminlte_lang::message.markcomplete') }}</label>
                        </div>
                    </div>
                </div>

                {{-- 60% complete --}}
                <div class="row margin-top10">
                    <div class="col-md-12 margin-left15">
                        <div class="col-md-3">
                            <label> {{ trans('adminlte_lang::message.60percent') }}</label>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group" style="width: 100%">
                                <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" name="60_percent_complete" value="{{ $project_details['milestones']['60_percent_complete'] }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-4 padding-top5">
                            <input type="checkbox" id="60_percent_complete" name="is_60_percent_complete" {{ $project_details['milestones']['is_60_percent_complete'] == 1 ? "checked" : "" }} disabled>
                            <label for="60_percent_complete"> {{ trans('adminlte_lang::message.markcomplete') }}</label>
                        </div>
                    </div>
                </div>

                {{-- 80% complete --}}
                <div class="row margin-top10">
                    <div class="col-md-12 margin-left15">
                        <div class="col-md-3">
                            <label> {{ trans('adminlte_lang::message.80percent') }}</label>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group" style="width: 100%">
                                <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" name="80_percent_complete" value="{{ $project_details['milestones']['80_percent_complete'] }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-4 padding-top5">
                            <input type="checkbox" id="80_percent_complete" name="is_80_percent_complete" {{ $project_details['milestones']['is_80_percent_complete'] == 1 ? "checked" : "" }} disabled>
                            <label for="80_percent_complete"> {{ trans('adminlte_lang::message.markcomplete') }}</label>
                        </div>
                    </div>
                </div>

                {{-- practical completion --}}
                <div class="row margin-top10">
                    <div class="col-md-12 margin-left15">
                        <div class="col-md-3">
                            <label> {{ trans('adminlte_lang::message.practicalcompletion') }}</label>
                        </div>
                        <div class="col-md-5">
                            <div class="input-group" style="width: 100%">
                                <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                                <input type="text" class="form-control" name="practical_completion" value="{{ $project_details['milestones']['practical_completion'] }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-4 padding-top5">
                            <input type="checkbox" id="practical_completion" name="is_practical_completion" {{ $project_details['milestones']['is_practical_completion'] == 1 ? "checked" : "" }} disabled>
                            <label for="practical_completion"> {{ trans('adminlte_lang::message.markcomplete') }}</label>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="row padding-10 margin-top30">
            <div class="col-md-12 text-right">
                <hr style="margin-top:12px; margin-bottom:12px;">
                <button type="button" id="" class="btn btn-link" data-dismiss="modal">{{ trans('adminlte_lang::message.close') }}</button>
                @if (session('_edit'))
                <button type="button" id="toggle-edit-milestones" data-id="{{ $project_details['pid'] }}" class="btn btn-primary">{{ trans('adminlte_lang::message.editmilestones') }}</button>
                @endif
                @if (session('_delete'))
                <button type="button" data-id="{{ $project_details['pid'] }}" class="btn btn-danger toggle-delete-project">{{ trans('adminlte_lang::message.delete') }}</button>
                @endif
            </div>
        </div>
    </div>

    {{-- changes --}}
    <div class="tab-pane" id="changes">
        <div class="row changes-wrapper">
            <div class="col-md-12">
                @if (count($changes))
                    @foreach ($changes as $change)
                    <div class="col-md-12 padding-10">
                        by {{$change['modified_by']}} on {{ date('M. jS' , strtotime($change['created_at'])) }}
                        <div class="bg-gray color-palette padding-10">
                            <em>{{ $change['comment'] }}</em>
                            <pre>{{ $change['reason'] }}</pre>
                        </div>
                    </div>
                    @endforeach
                @else
                <div class="col-md-12 padding-10">
                    <div class="bg-gray color-palette padding-10">
                        {{ trans('adminlte_lang::message.nochanges') }}
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div class="row padding-10 margin-top30">
            <div class="col-md-12 text-right">
                <hr style="margin-top:12px; margin-bottom:12px;">
                <button type="button" id="" class="btn btn-link" data-dismiss="modal">{{ trans('adminlte_lang::message.close') }}</button>
            </div>
        </div>
    </div>
</div>
