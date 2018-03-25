@extends('adminlte::layouts.app')

@section('htmlheader_title')
	{{ trans('adminlte_lang::message.home') }}
@endsection

@section('custom-css')
<link href="{{ url('/css/custom.css') }}" rel="stylesheet" type="text/css" />
@endsection


@section('main-content')
	<div class="container-fluid spark-screen">
		<div class="row">
		    <h3>{{ trans('adminlte_lang::message.projects') }}</h3>
		</div>

		<div class="row">
            <div id="success-alert" class="alert alert-success alert-dismissible hidden">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> {{ trans('adminlte_lang::message.projectdeleted') }}!</h4>
                <p id="message"></p>
            </div>

            <div id="error-alert" class="alert alert-danger alert-dismissible hidden">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> {{ trans('adminlte_lang::message.error') }}!</h4>
                <p id="message"></p>
              </div>
		</div>

        <div class="box">

            <div class="row padding-top17">
              <div class="col-md-4 col-xs-8">
                <div class="form-group">
                  <form id="search-form" class="navbar-form" role="search">
                    <div class="input-group" style="width:70%">
                      {!! Form::text('search_term', null, ['id' => 'search-term', 'class' => 'form-control', 'placeholder' => trans('adminlte_lang::message.search')]) !!}
                      <div class="input-group-btn">
                        <button id="toggle-search" class="btn btn-default" type="button">
                          <i class="glyphicon glyphicon-search"></i>
                        </button>
                      </div>
                    </div>
                    {{ csrf_field() }}
                  </form>
                </div>
              </div>

              <div class="col-md-4 col-md-offset-4">
				@if (session('_create'))
                <a href="javascript:void(0);" id="toggle-new-project" class="btn btn-default col-xs-4 pull-right margin-right10">
					{{ trans('adminlte_lang::message.newproject') }}
				</a>
				@endif
              </div>
            </div>

            <div class="row padding-top17">
                <div class="col-md-12">
                    <table class="table table-striped table-hover">
                      <thead>
                        <tr>
                          <!-- <th class="padding-left15 text-center col-xs-1">#</th> -->
                          <th class="padding-left25">{{ trans('adminlte_lang::message.projectname') }}</th>
                          <th>{{ trans('adminlte_lang::message.projectcode') }}</th>
                          <th>{{ trans('adminlte_lang::message.projecttype') }}</th>
                          <th>{{ trans('adminlte_lang::message.actions') }}</th>
                        </tr>
                      </thead>

                      <tbody id="projects-container">
                        @include('partials.Projects._list', ['projects' => $projects])
                      </tbody>
                    </table>
                </div>

                <div id="pagination" class="col-md-12 text-right padding-right25">
                    @if (count($projects))
                        {{ $projects->links() }}
                    @endif
				</div>
            </div>

        </div> <!-- end .box -->
	</div> <!-- end .container-fluid -->

    @include('partials.Projects._modal', ['projects' => $projects, 'project_types' => $project_types])

    <link rel="stylesheet" href="{{ url('/plugins/datepicker3.css') }}">
@endsection

@section('custom-script')
<!-- remove this if you use Modernizr -->
<script>(function(e,t,n){var r=e.querySelectorAll("html")[0];r.className=r.className.replace(/(^|\s)no-js(\s|$)/,"$1js$2")})(document,window,0);</script>

<script src="{{ url('js/class/projects.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    $jq(function() {
        var today = new Date();
        var endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);

        $jq('.milestones').datepicker({
            autoclose: true,
            todayHighlight: true,
			format: 'dd/mm/yyyy'
        });
    });
</script>
@endsection
