@extends('adminlte::layouts.app')

@section('htmlheader_title')
	{{ trans('adminlte_lang::message.myteam') }}
@endsection


@section('main-content')
<div class="container-fluid spark-screen">
	<div class="row">
		<h3>{{ trans('adminlte_lang::message.myteam1') }}</h3>
	</div>

	<div class="box">
		<div class="row padding-10">
			<div class="col-md-12 padding-top10">
				<div class="col-md-4 col-xs-8"></div>

				<div class="col-md-4 col-md-offset-4">
				  @if (session('_create'))
				  <a href="javascript:void(0);" id="toggle-add-team-member" class="btn btn-default pull-right margin-right10">
					  {{ trans('adminlte_lang::message.addteammember') }}
				  </a>
				  @endif
				</div>
			</div>
		</div>

		<div class="row padding-10">
			<div class="col-md-12">
				<table class="table table-striped">
					<thead>
						<tr>
							<th class="col-xs-3">{{ trans('adminlte_lang::message.headermembername') }}</th>
							<th class="col-xs-3">{{ trans('adminlte_lang::message.email') }}</th>
							<th class="col-xs-4">{{ trans('adminlte_lang::message.headermemberrole') }}</th>
							<th class="col-xs-2">&nbsp;</th>
						</tr>
					</thead>

					<tbody id="team-members-container">
					@include('partials.Users._list', compact('team_members'))
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

@include('partials.Users._modal')

@endsection

@section('custom-script')
<script src="{{ url('js/class/users.js') }}" type="text/javascript"></script>
@endsection
