@extends('adminlte::layouts.app')

@section('htmlheader_title')
	{{ trans('adminlte_lang::message.projecttype') }}
@endsection


@section('main-content')
<div class="container-fluid spark-screen">
	<div class="row">
		<h3>{{ trans('adminlte_lang::message.projecttype') }}</h3>
	</div>

	<div class="box">
		<div class="row padding-10">
			<div class="col-md-12 padding-top10">
				<div class="col-md-4 col-xs-8"></div>

				<div class="col-md-4 col-md-offset-4">
				  <a href="javascript:void(0);" id="toggle-add-project-type" class="btn btn-default pull-right margin-right10">
					  {{ trans('adminlte_lang::message.addprojecttype') }}
				  </a>
				</div>
			</div>
		</div>

		<div class="row padding-10">
			<div class="col-md-12">
				<table class="table table-striped">
					<thead>
						<tr>
							<th class="col-xs-1 text-center">#</th>
							<th class="col-xs-4">{{ trans('adminlte_lang::message.projecttype') }}</th>
							<th class="col-xs-7">&nbsp;</th>
						</tr>
					</thead>

					<tbody id="project-types-container">
						@include('partials.Home._project_type_list', compact('project_types'))
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

@include('partials.Home._project_type_modal')

@endsection

@section('custom-script')
<script src="{{ url('js/class/home.js') }}" type="text/javascript"></script>
@endsection
