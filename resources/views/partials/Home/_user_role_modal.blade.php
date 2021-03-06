<!-- modal: select new project -->
<div id="userRoleModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">{{ trans('adminlte_lang::message.adduserrole') }}</h4>
      </div>

      <div class="modal-body">
        @include('partials.Common._flash')

        <div id="user-roles-container" class="row padding-10">
          @include('partials.Home._user_role_form')
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" id="" class="btn btn-link" data-dismiss="modal">{{ trans('adminlte_lang::message.close') }}</button>
        <button type="button" id="toggle-save-userrole" class="btn btn-primary">{{ trans('adminlte_lang::message.save') }}</button>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- modal: edit -->
<div id="editUserRoleModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">{{ trans('adminlte_lang::message.edituserrole') }}</h4>
      </div>

      <div class="modal-body">
        @include('partials.Common._flash')

        <div id="user-role-info-container" class="row padding-10"></div>
      </div>

      <div class="modal-footer">
        <button type="button" id="" class="btn btn-link" data-dismiss="modal">{{ trans('adminlte_lang::message.close') }}</button>
        <button type="button" id="toggle-update-userrole" class="btn btn-primary">{{ trans('adminlte_lang::message.update') }}</button>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
