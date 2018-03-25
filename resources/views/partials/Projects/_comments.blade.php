@if (count($comments))
    @foreach ($comments as $comment)
    <div class="row padding-10">
        <div class="col-xs-7"><strong class="text-primary">{{ $comment->name }}</strong></div>
        @if (date('Y') == date('Y', strtotime($comment->created_at)))
        <div class="col-xs-5 text-right padding-right15">
            <small><em>
                {{ date('M. dS', strtotime($comment->created_at)) }}&nbsp;&nbsp;
                <i class="fa fa-clock-o" aria-hidden="true"></i>&nbsp;{{ date('H:i', strtotime($comment->created_at)) }}
            </em></small>
        </div>
        @else
        <div class="col-xs-5 text-right padding-right15">
            <small><em>
                {{ date('M. d, Y', strtotime($comment->created_at)) }}&nbsp;&nbsp;
                <i class="fa fa-clock-o" aria-hidden="true"></i>&nbsp;{{ date('H:i', strtotime($comment->created_at)) }}
            </em></small>
        </div>
        @endif
        <div class="col-xs-12">
            <p class="bg-info padding-10">{{ $comment->comment }}</p>
        </div>
    </div>
    @endforeach
@else
<div class="row">
    <div class="col-md-12 padding-20">
        <p>{{ trans('adminlte_lang::message.nocommentfound') }}</p>
    </div>
</div>
@endif
