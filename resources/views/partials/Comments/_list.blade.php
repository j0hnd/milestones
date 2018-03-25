@if (count($comments))
    @foreach ($comments as $comment)
    <div class="row padding-10 margin-10 bg-info form-horizontal bg-light-blue color-palette">
        <div class="col-md-12">
            <label>by <strong>{{ $comment['updated_by'] }}</strong> on <em>{{ date('M. jS', strtotime($comment['created_at'])) }}</em></label>
            <a href="#" class="toggle-comment-form pull-right" data-id="{{ $comment['log_id'] }}"><i class="fa fa-commenting" aria-hidden="true"></i> Reply</a>
        </div>
        <div class="col-md-12 bg-gray color-palette padding-10">
            <div class="form-group">
                <label class="col-sm-1 control-label text-left">{{ trans('adminlte_lang::message.changes') }}:</label>
                <div class="col-sm-11">
                    <strong>{{ $comment['comment'] }}</strong>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label text-left">{{ trans('adminlte_lang::message.comment') }}:</label>
                <div class="col-sm-11">
                    @if (count($comment['comments']) > 1)
                    <strong>{{ $comment['comments'][count($comment['comments']) - 1]['comment'] }}</strong>
                    @else
                    <strong>{{ $comment['comments'][0]['comment'] }}</strong>
                    @endif
                </div>
                <br/>

                @if ($comment['comments'])
                    @foreach ($comment['comments'] as $i => $_comments)
                    @if ($i != count($comment['comments']) - 1)
                    <div class="row margin-10">
                        <div class="col-sm-12 context padding-10">
                            <label>by <strong>{{ $_comments['comment_by'] }}</strong> on <em>{{ date('M. jS', strtotime($_comments['created_at'])) }}</em></label>
                            <div class="bg-gray disabled color-palette padding-10">
                                {{ $_comments['comment'] }}
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                @endif
            </div>
        </div>

        <div id="comment-form-{{ $comment['log_id'] }}" class="col-md-12 padding-10 context margin-top10 hidden">
            {!! Form::open(['id' => 'comment-form'.$comment['log_id'], 'method' => 'post']) !!}
            <div class="input-group">
                {!! Form::text('comment', null, ['id' => 'comment', 'class' => 'form-control', 'placeholder' => trans('adminlte_lang::message.typecommenthere')]) !!}
                <div class="input-group-btn">
                    <button id="toggle-save-comment" class="btn btn-success" type="button" data-id="{{ $comment['log_id'] }}">
                        <i class="glyphicon glyphicon-plus"></i>
                    </button>
                </div>
            </div>

            @if ($comment['comments'][count($comment['comments']) - 1]['object_name'] == 'details')
                {!! Form::hidden('pid', $comment['comments'][count($comment['comments']) - 1]['object_id']) !!}
            @else
                {!! Form::hidden('mid', $comment['comments'][count($comment['comments']) - 1]['object_id']) !!}
            @endif

            {!! Form::hidden('log_id', $comment['log_id']) !!}
            {!! Form::close() !!}
        </div>
    </div>
    @endforeach
@endif
