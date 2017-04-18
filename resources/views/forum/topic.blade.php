@extends ('layouts.master')

@include ('layouts.forum_header')

@section ('title')
{{$topic->title}}
@endsection

@section ('content')
<div class="topic">
  <div class="topic-title row">
    <div class="col-xs-10">
      <strong>{{$topic->title}}</strong>
      @can ('delete', $topic)
        <form method="POST">
	  {{csrf_field()}}
          {{method_field('DELETE')}}
	  <button type="submit" class="btn btn-default">{{trans('wzoj.delete')}}</button>
        </form>
      @endcan
    </div>
    <div class="col-xs-2">
      <a href="/users/{{$topic->user_id}}">{{$topic->user->name}}</a><br>
      @foreach ($topic->tags as $tag)
	{{$tag->value}}
      @endforeach
    </div>
  </div>
  @foreach ($replies as $reply)
  <div id="reply-{{$reply->id}}">
    <form action="/forum/replies/{{$reply->id}}" method="POST">
      {{csrf_field()}}
      {{method_field('PUT')}}
      <div class="topic-reply row">
        <div @can ('update', $reply) class="posteditor_inline" @endcan>
        {!!Purifier::clean($reply->content, 'forum')!!}
	</div>
      </div>
    </form>
    <div class="topic-reply row">
        #{{$reply->index}} <a href="/users/{{$reply->user_id}}">{{$reply->user->name}}</a>
	{{ojShortTime(strtotime($reply->updated_at))}}
	@can ('delete', $reply)
	  @if ($reply->index != 1)
	    <a href="#" onclick="deleteReply({{$reply->id}});return false;">{{trans('wzoj.delete')}}</a>
	  @endif
	@endcan
    </div>
  </div>
  <div class="top-buffer-sm"></div>
  @endforeach
</div>

<form class="form-horizontal row" method="POST" action="/forum/{{$topic->id}}">
  {{csrf_field()}}
  <textarea class='posteditor' id='content' name='content'></textarea>
  <button type="submit" class="btn btn-default">{{trans('wzoj.reply')}}</button>
</form>

@endsection

@section ('scripts')
<script>
function deleteReply(id){
	$.post("/forum/replies/" + id,{
		_token: "{{csrf_token()}}",
		_method: "DELETE"
	}, function(result){
	  $('#reply-'+id).remove();
	});
}
</script>
@endsection
