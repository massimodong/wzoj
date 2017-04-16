@extends ('layouts.master')

@include ('layouts.forum_header')

@section ('title')
{{$topic->title}}
@endsection

@section ('content')
<div class="topic">
  <div class="topic-title">{{$topic->title}}</div>
  @foreach ($replies as $reply)
    <div class="topic-reply">
      {!!Purifier::clean($reply->content, 'forum')!!}
    </div>
  @endforeach
</div>
@endsection
