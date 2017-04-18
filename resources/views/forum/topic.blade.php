@extends ('layouts.master')

@include ('layouts.forum_header')

@section ('title')
{{$topic->title}}
@endsection

@section ('content')

<form method="POST" id="delete-form">
{{csrf_field()}}
{{method_field('DELETE')}}
</form>

<div class="topic">
  <div class="topic-title row">
    <div class="col-xs-10">
      <div id="title">
        <strong>{{$topic->title}}</strong>
      </div>
      @can ('update', $topic)
        <a href="#" onclick="show_change_title_form();return false;">{{trans('wzoj.edit')}}</a>
      @endcan
      @can ('delete', $topic)
      	<a href="#" onclick="$('#delete-form').submit();return false;">{{trans('wzoj.delete')}}</a>
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
function show_change_title_form(){
	$('#title').html("<form method='POST'>"+
			"<input hidden name='_token' value='{{csrf_token()}}'>"+
			"<input hidden name='_method' value='PUT'>"+
			"<input name='title' value='{{$topic->title}}'>"+
			"<button type='submit' class='btn btn-default'>{{trans('wzoj.submit')}}</button>"+
			"</form>");
}
</script>
@endsection
