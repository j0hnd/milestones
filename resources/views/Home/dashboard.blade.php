@extends('adminlte::layouts.app')

@section('htmlheader_title')
	{{ trans('adminlte_lang::message.home') }}
@endsection

@section('custom-css')
<link href="{{ url('/css/custom.css') }}" rel="stylesheet" type="text/css" />
@endsection


@section('main-content')
<div class="container-fluid spark-screen">

	<div class="alert alert-success alert-dismissible hidden">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<h4><i class="icon fa fa-check"></i> Saved!</h4>
		<p id="alert-message">Success alert preview. This alert is dismissable.</p>
	</div>

	<div class="row">
	    <h3>{{ trans('adminlte_lang::message.dashboard') }}</h3>
	</div>

    <div class="box">

		<div class="row padding-top17">
          <div class="col-md-4 col-xs-8">
            <div class="form-group">
              <form id="search-form" class="navbar-form" role="search">
                <div class="input-group" style="width:70%">
                  {!! Form::text('search_term', null, ['id' => 'search-term', 'class' => 'form-control', 'placeholder' => trans('adminlte_lang::message.search')]) !!}
                  <div class="input-group-btn">
                    <button id="toggle-dashboard-search" class="btn btn-default" type="button">
                      <i class="glyphicon glyphicon-search"></i>
                    </button>
                  </div>
                </div>
                {{ csrf_field() }}
              </form>
            </div>
          </div>
	  </div>

		<div class="row padding-top17">
			<div class="col-md-12 table-responsive">
				<table id="dashboard-table" class="table table-hover">
				  <thead>
					<tr>
					  <!-- <th class="padding-left5 text-center">#</th> -->
					  <th  class="padding-left25"style="width: %">{{ trans('adminlte_lang::message.projectname') }}</th>
					  <th style="width: 11%" class="text-center">{{ trans('adminlte_lang::message.projectcode') }}</th>
					  <th style="width: 11%" class="text-center">{{ trans('adminlte_lang::message.projecttype') }}</th>
					  <th class="milestone-wrapper text-center">{{ trans('adminlte_lang::message.changes') }}</th>
					  <th class="milestone-wrapper text-center">Annouc.</th>
					  <th class="milestone-wrapper text-center">S/D</th>
					  <th class="milestone-wrapper text-center">Adv.</th>
					  <th class="milestone-wrapper text-center">Award</th>
					  <th class="milestone-wrapper text-center">Commenc.</th>
					  <th class="milestone-wrapper text-center">20%</th>
					  <th class="milestone-wrapper text-center">40%</th>
					  <th class="milestone-wrapper text-center">60%</th>
					  <th class="milestone-wrapper text-center">80%</th>
					  <th class="milestone-wrapper text-center">P.C.</th>
					  <th style="width: 7%">{{ trans('adminlte_lang::message.projectmanager') }}</th>
					</tr>
				  </thead>

				  <tbody id="dashboard-container">
				  @include('partials.Home._list', compact('summary'))
				  </tbody>
				</table>
			</div>

			<div id="pagination" class="col-md-12 text-right padding-right25">
				@if (count($raw_summary))
					{{ $raw_summary->links() }}
				@endif
			</div>
		</div>

    </div>
</div>
@endsection

@section('custom-script')
<script src="{{ url('plugins/jquery.cookie.js') }}" type="text/javascript"></script>
<script src="{{ url('js/class/home.js') }}" type="text/javascript"></script>
<script src="{{ url('js/class/dashboard.js') }}" type="text/javascript"></script>
<script src="{{ url('js/class/comments.js') }}" type="text/javascript"></script>
<script src="{{ url('js/class/refresh.js') }}" type="text/javascript"></script>
@endsection
