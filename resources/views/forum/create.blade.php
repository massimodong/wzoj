@extends ('layouts.master')

@include ('layouts.forum_header')

@section ('title')
{{trans('wzoj.new_post')}}
@endsection

@section ('sidebar')
<li><a href="#" onclick="post_topic();return false;">{{trans('wzoj.new_post')}}</a></li>
@endsection

@section ('content')
<form class="form-horizontal" method="POST" action="/forum" id="new_post_form">
  {{csrf_field()}}
  <div class="form-group">
    <label class="control-label sr-only" for="title">{{trans('wzoj.title')}}</label>
    <div class="col-xs-offset-1 col-xs-10">
      <input type="text" class="form-control" id="title" name="title" placeholder="{{trans('wzoj.title')}}" required>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label sr-only" for="content">{{trans('wzoj.content')}}</label>
    <div class="col-xs-offset-1 col-xs-10">
      <textarea class='posteditor' id='content' name='content' required></textarea>
    </div>
  </div>

</form>
@endsection

@section ('scripts')
<script>
function post_topic(){
	if($('#title').val() == ''){
		alert("{{trans('wzoj.title_needed')}}");
		return;
	}
	$('#new_post_form').submit();
}
</script>
@endsection
