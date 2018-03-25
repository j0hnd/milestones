<div class="row">
    <div class="col-md-12">
        <form id="update-milestones" method="post" class="form-inline padding-top17">
            {{-- announcement --}}
            <div class="row">
                <div class="col-md-12 margin-left15">
                    <div class="col-md-3">
                        <label> {{ trans('adminlte_lang::message.announcement') }}</label>
                    </div>
                    <div class="col-md-5">
                        <div class="input-group" style="width: 100%">
                            <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                            <input type="text" class="form-control milestones form-changes" name="announcement" data-index="milestones" data-field="announcement" data-original="{{ $project_details['milestones']['announcement'] }}" value="{{ $project_details['milestones']['announcement'] }}" placeholder="dd/mm/yyyy">
                        </div>
                    </div>
                    <div class="col-md-4 padding-top5">
                        <input type="checkbox" class="form-changes" id="mark-complete-announcement" name="is_announcement" {{ $project_details['milestones']['is_announcement'] == 1 ? "checked" : "" }} data-index="milestones" data-field="announcement-completed"  data-original="{{ date('/d/m/Y', strtotime($project_details['milestones']['is_announcement'])) }}" data-toggle="tooltip" title="{{ trans('adminlte_lang::message.markcomplete') }}">
                        <label for="mark-complete-announcement"> {{ trans('adminlte_lang::message.markcomplete') }}</label>
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
                            <input type="text" class="form-control milestones form-changes" name="scoping_design" data-index="milestones" data-field="scoping_design" data-original="{{ $project_details['milestones']['scoping_design'] }}" value="{{ $project_details['milestones']['scoping_design'] }}" placeholder="dd/mm/yyyy">
                        </div>
                    </div>
                    <div class="col-md-4 padding-top5">
                        <input type="checkbox" class="form-changes" id="mark-complete-scoping_design" name="is_scoping_design" {{ $project_details['milestones']['is_scoping_design'] == 1 ? "checked" : "" }} data-index="milestones" data-field="scoping-design-completed"  data-original="{{ $project_details['milestones']['is_scoping_design'] }}" data-toggle="tooltip" title="{{ trans('adminlte_lang::message.markcomplete') }}">
                        <label for="mark-complete-scoping_design"> {{ trans('adminlte_lang::message.markcomplete') }}</label>
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
                            <input type="text" class="form-control milestones form-changes" name="advertising" data-index="milestones" data-field="advertising" data-original="{{ $project_details['milestones']['advertising'] }}" value="{{ $project_details['milestones']['advertising'] }}" placeholder="dd/mm/yyyy">
                        </div>
                    </div>
                    <div class="col-md-4 padding-top5">
                        <input type="checkbox" class="form-changes" id="mark-complete-advertising" name="is_advertising" {{ $project_details['milestones']['is_advertising'] == 1 ? "checked" : "" }} data-index="milestones" data-field="advertising-completed"  data-original="{{ $project_details['milestones']['is_advertising'] }}" data-toggle="tooltip" title="{{ trans('adminlte_lang::message.markcomplete') }}">
                        <label for="mark-complete-advertising"> {{ trans('adminlte_lang::message.markcomplete') }}</label>
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
                            <input type="text" class="form-control milestones form-changes" name="award" data-index="milestones" data-field="award" data-original="{{ $project_details['milestones']['award'] }}" value="{{ $project_details['milestones']['award'] }}" placeholder="dd/mm/yyyy">
                        </div>
                    </div>
                    <div class="col-md-4 padding-top5">
                        <input type="checkbox" class="form-changes" id="mark-complete-award" name="is_award" {{ $project_details['milestones']['is_award'] == 1 ? "checked" : "" }} data-index="milestones" data-field="award-completed"  data-original="{{ $project_details['milestones']['is_award'] }}" data-toggle="tooltip" title="{{ trans('adminlte_lang::message.markcomplete') }}">
                        <label for="mark-complete-award"> {{ trans('adminlte_lang::message.markcomplete') }}</label>
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
                            <input type="text" class="form-control milestones form-changes" name="commencement" data-index="milestones" data-field="commencement" data-original="{{ $project_details['milestones']['commencement'] }}" value="{{ $project_details['milestones']['commencement'] }}" placeholder="dd/mm/yyyy">
                        </div>
                    </div>
                    <div class="col-md-4 padding-top5">
                        <input type="checkbox" class="form-changes" id="mark-complete-commencement" name="is_commencement" {{ $project_details['milestones']['is_commencement'] == 1 ? "checked" : "" }} data-index="milestones" data-field="commencement-completed"  data-original="{{ $project_details['milestones']['is_commencement'] }}" data-toggle="tooltip" title="{{ trans('adminlte_lang::message.markcomplete') }}">
                        <label for="mark-complete-commencement"> {{ trans('adminlte_lang::message.markcomplete') }}</label>
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
                            <input type="text" class="form-control milestones form-changes" name="20_percent_complete" data-index="milestones" data-field="20_percent_complete" data-original="{{ $project_details['milestones']['20_percent_complete'] }}" value="{{ $project_details['milestones']['20_percent_complete'] }}" placeholder="dd/mm/yyyy">
                        </div>
                    </div>
                    <div class="col-md-4 padding-top5">
                        <input type="checkbox" class="form-changes" id="mark-complete-20_percent_complete" name="is_20_percent_complete" {{ $project_details['milestones']['is_20_percent_complete'] == 1 ? "checked" : "" }} data-index="milestones" data-field="20-percent-completed"  data-original="{{ $project_details['milestones']['is_20_percent_complete'] }}" data-toggle="tooltip" title="{{ trans('adminlte_lang::message.markcomplete') }}">
                        <label for="mark-complete-20_percent_complete"> {{ trans('adminlte_lang::message.markcomplete') }}</label>
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
                            <input type="text" class="form-control milestones form-changes" name="40_percent_complete" data-index="milestones" data-field="40_percent_complete" data-original="{{ $project_details['milestones']['40_percent_complete'] }}" value="{{ $project_details['milestones']['40_percent_complete'] }}" placeholder="dd/mm/yyyy">
                        </div>
                    </div>
                    <div class="col-md-4 padding-top5">
                        <input type="checkbox" class="form-changes" id="mark-complete-40_percent_complete" name="is_40_percent_complete" {{ $project_details['milestones']['is_40_percent_complete'] == 1 ? "checked" : "" }} data-index="milestones" data-field="40-percent-completed"  data-original="{{ $project_details['milestones']['is_40_percent_complete'] }}" data-toggle="tooltip" title="{{ trans('adminlte_lang::message.markcomplete') }}">
                        <label for="mark-complete-40_percent_complete"> {{ trans('adminlte_lang::message.markcomplete') }}</label>
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
                            <input type="text" class="form-control milestones form-changes" name="60_percent_complete" data-index="milestones" data-field="60_percent_complete" data-original="{{ $project_details['milestones']['60_percent_complete'] }}" value="{{ $project_details['milestones']['60_percent_complete'] }}" placeholder="dd/mm/yyyy">
                        </div>
                    </div>
                    <div class="col-md-4 padding-top5">
                        <input type="checkbox" class="form-changes" id="mark-complete-60_percent_complete" name="is_60_percent_complete" {{ $project_details['milestones']['is_60_percent_complete'] == 1 ? "checked" : "" }} data-index="milestones" data-field="60-percent-completed"  data-original="{{ $project_details['milestones']['is_60_percent_complete'] }}" data-toggle="tooltip" title="{{ trans('adminlte_lang::message.markcomplete') }}">
                        <label for="mark-complete-60_percent_complete"> {{ trans('adminlte_lang::message.markcomplete') }}</label>
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
                            <input type="text" class="form-control milestones form-changes" name="80_percent_complete" data-index="milestones" data-field="80_percent_complete" data-original="{{ $project_details['milestones']['80_percent_complete'] }}" value="{{ $project_details['milestones']['80_percent_complete'] }}" placeholder="dd/mm/yyyy">
                        </div>
                    </div>
                    <div class="col-md-4 padding-top5">
                        <input type="checkbox" class="form-changes" id="mark-complete-80_percent_complete" name="is_80_percent_complete" {{ $project_details['milestones']['is_80_percent_complete'] == 1 ? "checked" : "" }} data-index="milestones" data-field="80-percent-completed"  data-original="{{ $project_details['milestones']['is_80_percent_complete'] }}" data-toggle="tooltip" title="{{ trans('adminlte_lang::message.markcomplete') }}">
                        <label for="mark-complete-80_percent_complete"> {{ trans('adminlte_lang::message.markcomplete') }}</label>
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
                            <input type="text" class="form-control milestones form-changes" name="practical_completion" data-index="milestones" data-field="practicalcompletion" data-original="{{ $project_details['milestones']['practical_completion'] }}" value="{{ $project_details['milestones']['practical_completion'] }}" placeholder="dd/mm/yyyy">
                        </div>
                    </div>
                    <div class="col-md-4 padding-top5">
                        <input type="checkbox" class="form-changes" id="mark-complete-practical_completion" name="is_practical_completion" {{ $project_details['milestones']['is_practical_completion'] == 1 ? "checked" : "" }} data-index="milestones" data-field="practical-completion-completed"  data-original="{{ $project_details['milestones']['is_practical_completion'] }}" data-toggle="tooltip" title="{{ trans('adminlte_lang::message.markcomplete') }}">
                        <label for="mark-complete-practical_completion"> {{ trans('adminlte_lang::message.markcomplete') }}</label>
                    </div>
                </div>
            </div>

            {!! Form::hidden('mid', $project_details['milestones']['mid']) !!}
            {{ csrf_field() }}
        </form>
    </div>
</div>
<div class="row padding-10 margin-top30">
    <div class="col-md-12 text-right">
        <hr style="margin-top:12px; margin-bottom:12px;">
        <button type="button" id="" class="btn btn-link" data-dismiss="modal">{{ trans('adminlte_lang::message.close') }}</button>
        <button type="button" id="toggle-update-milestones" data-id="{{ $project_details['milestones']['mid'] }}" class="btn btn-primary">{{ trans('adminlte_lang::message.updatemilestones') }}</button>
    </div>
</div>

<script type="text/javascript">
    $jq(function() {
        $jq('.milestones').datepicker({
            autoclose: true,
            todayHighlight: true,
            format: 'dd/mm/yyyy'
        });
    });
</script>
