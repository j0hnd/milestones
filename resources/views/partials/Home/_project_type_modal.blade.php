<!-- modal: select new project -->
<div id="projectTypeModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">{{ trans('adminlte_lang::message.addprojecttype') }}</h4>
      </div>

      <div class="modal-body">
        @include('partials.Common._flash')

        <div id="proejct_type-container" class="row padding-10">
          @include('partials.Home._project_type_form')
        </div>
      </div>

      <div class="modal-footer">
        <button type="button" id="" class="btn btn-link" data-dismiss="modal">{{ trans('adminlte_lang::message.close') }}</button>
        <button type="button" id="toggle-save-projecttype" class="btn btn-primary">{{ trans('adminlte_lang::message.save') }}</button>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- modal: edit -->
<div id="editProjectTypeModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">{{ trans('adminlte_lang::message.editprojecttype') }}</h4>
      </div>

      <div class="modal-body">
        @include('partials.Common._flash')

        <div id="project-type-info-container" class="row padding-10"></div>
      </div>

      <div class="modal-footer">
        <button type="button" id="" class="btn btn-link" data-dismiss="modal">{{ trans('adminlte_lang::message.close') }}</button>
        <button type="button" id="toggle-update-projecttype" class="btn btn-primary">{{ trans('adminlte_lang::message.update') }}</button>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
