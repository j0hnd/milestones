@extends('adminlte::layouts.errors')

@section('htmlheader_title')
	{{ trans('adminlte_lang::message.passwordreset') }}
@endsection

@section('main-content')
<div class="row">
    <div class="col-md-6 col-md-offset-3">
        @if ($success)
        <div class="message-container alert alert-success">
            <strong>Success!</strong> {{ trans('adminlte_lang::message.passwordreset') }}<br>
        </div>
        @else
        <div class="error-container">
            <div class="alert alert-danger">
                <strong>Whoops!</strong> {{ trans('adminlte_lang::message.someproblems') }}<br>
                <ul class="error-wrapper">
                    <li>{{ $message }}</li>
                </ul>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
