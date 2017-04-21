@extends ('layouts.master')

@include ('layouts.forum_header')

@section ('title')
{{ojoption('site_name')}} {{trans('wzoj.bbs')}}
@endsection

@section ('sidebar')
<li id='home_sidebar'><a href="/"> {{trans('wzoj.home')}} </a></li>
<li id='newpost_sidebar' class="new_post_sidebar"><a href="/forum/create" style="color:#FFFFFF;"> {{trans('wzoj.new_post')}} </a></li>
@endsection

@section ('content')
<!--
<div class="col-xs-offset-1 col-xs-11 row">
  <form action="/forum/search" method="GET">
    <div class="form-group col-xs-8">
      <input type="text" class="form-control" id="search_text" name="search_text">
    </div>
    <button type="submit" class="btn btn-default">{{trans('wzoj.search')}}</button>
  </form>
</div>
-->
<div class="col-xs-12">
<hr>
<div id="show_topics">
</div>
</div>
@endsection

@section ('scripts')
<script>
var shownTopics = {};
var isExpanding = true;
var initTopics = {!! json_encode($topics) !!};
var lastTime = initTopics[initTopics.length - 1].updated_at;
function addTopic(topic, index){
	if(shownTopics[topic.id]){
		return;
	}
	shownTopics[topic.id] = true;
	$row = $("<div class='topic_index row'></div>");

	$div_content = $("<div class='col-xs-10'></div>");
	$div_content.append("<a href='/forum/" + topic.id + "'>" + topic.title + "</a><br>");
	$div_content.append("<div class='topic_index_content'>" + topic.preview + "</div>");
	$row.append($($div_content));

	$div_user = $("<div class='col-xs-2'></div>");
	$div_user.append("<a href='/users/" + topic.user_id + "'>" + topic.user_name + "</a><br>");
	$div_user.append(topic.updated_time + "<br>");
	$div_user.append("<span class='glyphicon glyphicon-eye-open' style='color:grey'></span>" + topic.cnt_views);
	$row.append($($div_user));

	$('#show_topics').append($($row));
}

function expand(){
	if(isExpanding){
		return;
	}
	isExpanding = true;
	$.get("/forum/ajax-get-topics", {last_time:lastTime})
		.done(function( data ){
			if(data.length == 0){
				return;
			}
			data.forEach(addTopic);
			lastTime = data[data.length - 1].updated_at;
			isExpanding = false;
		});
}

$(window).scroll(function() {
	if($(window).scrollTop() + $(window).height() >= $(document).height() - 10) {
		expand();
	}
});

jQuery(document).ready(function($) {
	initTopics.forEach(addTopic);
	isExpanding = false;
});

</script>
@endsection
