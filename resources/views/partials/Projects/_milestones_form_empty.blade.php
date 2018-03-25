{{-- announcement --}}
<div class="row  padding-top17">
    <div class="col-md-12 margin-left15">
        <div class="col-md-3">
            <label> {{ trans('adminlte_lang::message.announcement') }}</label>
        </div>
        <div class="col-md-5">
            <div class="input-group" style="width: 100%">
                <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                <input type="text" class="form-control milestones" name="announcement" placeholder="dd/mm/yyyy">
            </div>
        </div>
        <div class="col-md-4 padding-top5">
            <input type="checkbox" class="form-changes" id="announcement" name="is_announcement" data-toggle="tooltip" title="{{ trans('adminlte_lang::message.markcomplete') }}">
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
                <input type="text" class="form-control milestones" name="scoping_design" placeholder="dd/mm/yyyy">
            </div>
        </div>
        <div class="col-md-4 padding-top5">
            <input type="checkbox" class="form-changes" id="scoping_design" name="is_scoping_design" data-toggle="tooltip" title="{{ trans('adminlte_lang::message.markcomplete') }}">
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
                <input type="text" class="form-control milestones" name="advertising" placeholder="dd/mm/yyyy">
            </div>
        </div>
        <div class="col-md-4 padding-top5">
            <input type="checkbox" class="form-changes" id="advertising" name="is_advertising" data-toggle="tooltip" title="{{ trans('adminlte_lang::message.markcomplete') }}">
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
                <input type="text" class="form-control milestones" name="award" placeholder="dd/mm/yyyy">
            </div>
        </div>
        <div class="col-md-4 padding-top5">
            <input type="checkbox" class="form-changes" id="award" name="is_award" data-toggle="tooltip" title="{{ trans('adminlte_lang::message.markcomplete') }}">
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
                <input type="text" class="form-control milestones" name="commencement" placeholder="dd/mm/yyyy">
            </div>
        </div>
        <div class="col-md-4 padding-top5">
            <input type="checkbox" class="form-changes" id="commencement" name="is_commencement" data-toggle="tooltip" title="{{ trans('adminlte_lang::message.markcomplete') }}">
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
                <input type="text" class="form-control milestones" name="20_percent_complete" placeholder="dd/mm/yyyy">
            </div>
        </div>
        <div class="col-md-4 padding-top5">
            <input type="checkbox" class="form-changes" id="20_percent_complete" name="is_20_percent_complete" data-toggle="tooltip" title="{{ trans('adminlte_lang::message.markcomplete') }}">
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
                <input type="text" class="form-control milestones" name="40_percent_complete" placeholder="dd/mm/yyyy">
            </div>
        </div>
        <div class="col-md-4 padding-top5">
            <input type="checkbox" class="form-changes" id="40_percent_complete" name="is_40_percent_complete" data-toggle="tooltip" title="{{ trans('adminlte_lang::message.markcomplete') }}">
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
                <input type="text" class="form-control milestones" name="60_percent_complete" placeholder="dd/mm/yyyy">
            </div>
        </div>
        <div class="col-md-4 padding-top5">
            <input type="checkbox" class="form-changes" id="60_percent_complete" name="is_60_percent_complete" data-toggle="tooltip" title="{{ trans('adminlte_lang::message.markcomplete') }}">
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
                <input type="text" class="form-control milestones" name="80_percent_complete" placeholder="dd/mm/yyyy">
            </div>
        </div>
        <div class="col-md-4 padding-top5">
            <input type="checkbox" class="form-changes" id="80_percent_complete" name="is_80_percent_complete" data-toggle="tooltip" title="{{ trans('adminlte_lang::message.markcomplete') }}">
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
                <input type="text" class="form-control milestones" name="practical_completion" placeholder="dd/mm/yyyy">
            </div>
        </div>
        <div class="col-md-4 padding-top5">
            <input type="checkbox" class="form-changes" id="practical_completion" name="is_practical_completion" data-toggle="tooltip" title="{{ trans('adminlte_lang::message.markcomplete') }}">
            <label for="practical_completion"> {{ trans('adminlte_lang::message.markcomplete') }}</label>
        </div>
    </div>
</div>
