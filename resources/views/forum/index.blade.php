@extends ('layouts.master')

@include ('layouts.forum_header')

@section ('title')
{{ojoption('site_name')}} {{trans('wzoj.bbs')}}
@endsection

@section ('sidebar')
<li id='newpost_sidebar' class="new_post_sidebar"><a href="/forum/create" style="color:#FFFFFF;"> {{trans('wzoj.new_post')}} </a></li>
@endsection

@section ('content')
<div class="col-xs-offset-1 col-xs-11 row">
  <form action="/forum/search" method="GET">
    <div class="form-group col-xs-8">
      <input type="text" class="form-control" id="search_text" name="search_text">
    </div>
    <button type="submit" class="btn btn-default">{{trans('wzoj.search')}}</button>
  </form>
</div>
<div class="col-xs-12">
<hr>
@foreach ($topics as $topic)
  <div class="topic_index row">
    <div class="col-xs-11">
      <a href="/forum/{{$topic->id}}">{{$topic->title}}</a><br>
      fafdafsafas...todo...
    </div>
    <div class="col-xs-1">
      <a href="/users/{{$topic->user->id}}">{{$topic->user->name}}</a><br>
      {{ojShortTime(strtotime($topic->updated_at))}}<br>
      <span class="glyphicon glyphicon-eye-open" style="color:grey"></span> {{$topic->cnt_views}}
    </div>
  </div>
@endforeach
</div>
@endsection
