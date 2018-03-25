<!-- modal: user profile -->
<div id="profileModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">{{ trans('adminlte_lang::message.userprofile') }}</h4>
      </div>

      <div class="modal-body">
        @include('partials.Common._flash')

        <div id="user-profile-container" class="row padding-10">

        </div>
      </div>

      <div class="modal-footer">
        <button type="button" id="" class="btn btn-link" data-dismiss="modal">{{ trans('adminlte_lang::message.close') }}</button>
        <button type="button" id="toggle-save-userprofile" class="btn btn-primary">{{ trans('adminlte_lang::message.save') }}</button>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- modal: forgot password -->
<div id="forgotPasswordModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">{{ trans('adminlte_lang::message.sendpassword') }}</h4>
      </div>

      <div class="modal-body">
        @include('partials.Common._flash')

        <div id="forgot-password-container" class="row padding-10">
          {!! Form::open(['id' => 'forgot-password-form', 'method' => 'post']) !!}
              <div class="form-group has-feedback">
                  <input type="email" class="form-control" placeholder="{{ trans('adminlte_lang::message.email') }}" name="email" autofocus/>
                  <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
              </div>
          {!! Form::close() !!}
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" id="" class="btn btn-link" data-dismiss="modal">{{ trans('adminlte_lang::message.close') }}</button>
        <button type="button" id="toggle-send-reset-link" class="btn btn-primary">{{ trans('adminlte_lang::message.send') }}</button>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
