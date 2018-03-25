@extends('adminlte::layouts.app')

@section('htmlheader_title')
	{{ trans('adminlte_lang::message.userroles') }}
@endsection


@section('main-content')
<div class="container-fluid spark-screen">
	<div class="row">
		<h3>{{ trans('adminlte_lang::message.userroles') }}</h3>
	</div>

	<div class="box">
		<div class="row padding-10">
			<div class="col-md-12 padding-top10">
				<div class="col-md-4 col-xs-8"></div>

				<div class="col-md-4 col-md-offset-4">
				  <a href="javascript:void(0);" id="toggle-add-user-role" class="btn btn-default pull-right margin-right10">
					  {{ trans('adminlte_lang::message.adduserroles') }}
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
							<th class="col-xs-3">{{ trans('adminlte_lang::message.userroles') }}</th>
							<th class="col-xs-1 text-center">{{ trans('adminlte_lang::message._create') }}</th>
							<th class="col-xs-1 text-center">{{ trans('adminlte_lang::message._edit') }}</th>
							<th class="col-xs-1 text-center">{{ trans('adminlte_lang::message._view') }}</th>
							<th class="col-xs-1 text-center">{{ trans('adminlte_lang::message._delete') }}</th>
							<th class="col-xs-1 text-center">{{ trans('adminlte_lang::message.notifyemail') }}</th>
							<th class="col-xs-1 text-center">{{ trans('adminlte_lang::message.isadmin_header') }}</th>
							<th class="col-xs-2" >&nbsp;</th>
						</tr>
					</thead>

					<tbody id="user-roles-container">
						@include('partials.Home._user_role_list', compact('user_roles'))
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

@include('partials.Home._user_role_modal')
@endsection

@section('custom-script')
<script src="{{ url('js/class/home.js') }}" type="text/javascript"></script>
@endsection
