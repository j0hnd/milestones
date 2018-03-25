<!-- modal: select new project -->
<div id="projectModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">{{ trans('adminlte_lang::message.createproject') }}</h4>
      </div>

      <div class="modal-body">
        <div class="row padding-top15">
          <div class="col-xs-6 text-center">
            <a href="javascript:void(0)" id="toggle-new-project-form">
              <i class="fa fa-pencil-square-o fa-5x" aria-hidden="true"></i>
              <h3>{{ trans('adminlte_lang::message.createnewproject') }}</h3>
            </a>
          </div>

          <div class="col-xs-6 text-center">
            <a href="javascript:void(0)" id="toggle-upload-form">
              <i class="fa fa-upload fa-5x" aria-hidden="true"></i>
              <h3>{{ trans('adminlte_lang::message.uploadnewproject') }}</h3>
            </a>
          </div>
        </div>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- modal: add new project -->
<div id="uploadProject" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">{{ trans('adminlte_lang::message.createproject') }}</h4>
      </div>

      <div class="modal-body">
        <form id="upload-projects" method="post" enctype="multipart/form-data">

          @include('partials.Common._flash')

          <div class="row">
            <div class="col-xs-12 text-center">
              <h4>{{ trans('adminlte_lang::message.uploadsubtitle') }}</h4>
            </div>
            <div class="col-xs-12 download-template-wrapper text-center margin-bottom-10">
                <a href="{{ url('/template/project-template.csv') }}">{{ trans('adminlte_lang::message.downloadtemplate') }}</a>
            </div>
          </div>

          <div class="row margin-top20">
            <div class="col-md-6 col-md-offset-4">
              <input type="file" id="csv" name="csv" class="custom-file-input" />
            </div>
          </div>

          <div class="row margin-top5">
            <div class="col-md-6 col-md-offset-3">
              <button type="button" id="toggle-upload-csv" class="btn btn-default" style="width:100%">Upload</button>
            </div>
          </div>

          <div class="row margin-top5">
            <div class="col-md-12">
              <div id="status" class="padding-10 text-center"></div>
            </div>
          </div>

          <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}">
        </form>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- modal: add new project -->
<div id="addProject" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">{{ trans('adminlte_lang::message.createproject') }}</h4>
      </div>

      <div class="modal-body padding-25">
        @include('partials.Common._flash')

        <ul class="nav nav-tabs">
          <li class="active"><a href="#empty-details" data-toggle="tab">{{ trans('adminlte_lang::message.details') }}</a></li>
          <li><a href="#empty-milestones" data-toggle="tab">{{ trans('adminlte_lang::message.milestones') }}</a></li>
        </ul>

        <form id="projects" method="post">
          <div class="tab-content clearfix">
            <div class="tab-pane active" id="empty-details">
              <div class="row"> @include('partials.Projects._details', ['projects' => $projects, 'project_types' => $project_types]) </div>
            </div>

            <div class="tab-pane" id="empty-milestones">
              <div class="row"> @include('partials.Projects._milestones_form_empty') </div>
            </div>
          </div>

          {{ csrf_field() }}
        </form>
      </div>

      <div class="modal-footer">
        <button type="button" id="" class="btn btn-link" data-dismiss="modal">{{ trans('adminlte_lang::message.close') }}</button>
        <button type="button" id="toggle-save-project" class="btn btn-primary">{{ trans('adminlte_lang::message.save') }}</button>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- modal: edit project -->
<div id="editProject" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title">{{ trans('adminlte_lang::message.projectdetails') }}</h4>
      </div>

      <div class="modal-body padding-25">
        @include('partials.Common._flash')

        <ul class="nav nav-tabs">
          <li class="active"><a href="#details" id="toggle-tab-details" data-toggle="tab">{{ trans('adminlte_lang::message.details') }}</a></li>
          <li><a href="#milestones" id="toggle-tab-milestones" data-toggle="tab">{{ trans('adminlte_lang::message.milestones') }}</a></li>
          <li><a href="#changes" data-toggle="tab">{{ trans('adminlte_lang::message.changes') }}</a></li>
        </ul>

        <div id="edit-project-details-container" class="row"></div>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- modal: comment modal -->
<div id="commentModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <button type="button" id="commentModal-close-btn" class="close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">{{ trans('adminlte_lang::message.comments') }}</h4>
      </div>

      <div class="modal-body padding-25">
        @include('partials.Common._flash')

        <form id="comment-form" method="post">
          <p>Write a brief reason why you make changes on the project details.</p>
          <div class="input-group">
            {!! Form::text('comment', null, ['id' => 'comment', 'class' => 'form-control', 'placeholder' => trans('adminlte_lang::message.typecommenthere')]) !!}
            <div class="input-group-btn">
              <button id="toggle-add-comment" class="btn btn-success" type="button" data-confirm="{{ trans('adminlte_lang::message.closecommentmodal') }}">
              {{--<i class="glyphicon glyphicon-plus"></i>--}}
                Submit Changes
              </button>
            </div>
          </div>

          {{ csrf_field() }}
          {!! Form::hidden('pid', null, ['id' => 'pid']) !!}
          {!! Form::hidden('mid', null, ['id' => 'mid']) !!}
          {!! Form::hidden('form', null, ['id' => 'form']) !!}
          {!! Form::hidden('log_id', null, ['id' => 'log-id']) !!}
        </form>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- modal: close modal confirmt -->
<div id="dataConfirmModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title">{{ trans('adminlte_lang::message.closecommentbox') }}</h4>
      </div>

      <div class="modal-body padding-25">
        <p>{{ trans('adminlte_lang::message.closecommentmodal') }}</p>
      </div>

      <div class="modal-footer">
        <button type="button" id="" class="btn btn-default" data-dismiss="modal">{{ trans('adminlte_lang::message.makecomment') }}</button>
        <button type="button" id="toggle-dont-save-changes" class="btn btn-danger">{{ trans('adminlte_lang::message.dontsavechanges') }}</button>
      </div>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
