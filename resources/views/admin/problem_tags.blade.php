@extends ('admin.layout')

@section ('title')
{{trans('wzoj.tags')}}
@endsection (title')

@section ('head')
@parent
<link href="/include/css/dd-nestable.css" rel="stylesheet">
@endsection

@section ('content')

<div class="dd-nestable col-xs-4" id="tags-nestable">
  @include ('admin.problem_tags_recursive', ['tags' => $tags->filter(function($value){
			    return $value->parent_id == 0;
			  })->sortBy('index')])
</div>
<div class="col-xs-offset-6 col-xs-3 fixed row" id="rightbar">
  <form id="tag-form" method="POST" class="col-xs-12 fixed-form">
    {{csrf_field()}}
    {{method_field('PUT')}}

    <div class="form-group">
      <label for="showTagId"> {{trans('wzoj.id')}} </label>
      <input type="text" class="form-control" id="showTagId" disabled>
    </div>
    <div class="form-group">
      <label for="name"> {{trans('wzoj.name')}} </label>
      <input type="text" class="form-control" id="name" name="name">
    </div>
    <div class="form-group">
      <label for="aliases"> {{trans('wzoj.aliases')}} </label>
      <input type="text" class="form-control" id="aliases" name="aliases">
    </div>
    <div class="form-group">
      <label for="reference_url"> {{trans('wzoj.reference_url')}} </label>
      <input type="text" class="form-control" id="reference_url" name="reference_url">
    </div>
    <button type="submit" class="btn btn-default" id="update-hierarchy-button"> {{trans('wzoj.update')}} </button>
  </form>

  <div class="col-xs-12" style="height:10px;"></div>

  <form class="col-xs-12 fixed-form" action="/admin/problem-tags/hierarchy" method="POST">
    {{csrf_field()}}
    {{method_field('PUT')}}
    <input hidden name="tags" id="tags-hierarchy">
    <button type="submit" class="btn btn-default" id="save-changes-button" disabled onclick="$('#tags-hierarchy').val(JSON.stringify($('#tags-nestable').nestable('serialize'),null,2))">
    {{trans('wzoj.save_changes')}} </button>
    <button type="submit" class="btn btn-default" id="undo-changes-button" disabled onclick="location.reload();return false;"> {{trans('wzoj.undo_changes')}} </button>
    <button type="submit" class="btn btn-default" onclick="tagsExpandAll();return false;"> {{trans('wzoj.expand_all')}} </button>
    <button type="submit" class="btn btn-default" onclick="tagsCollapseAll();return false;"> {{trans('wzoj.collapse_all')}} </button>
  </form>

  <div class="col-xs-12" style="height:10px;"></div>

  <form class="col-xs-12 fixed-form" action="/admin/problem-tags" method="POST">
    {{csrf_field()}}
    <button type="submit" class="btn btn-default"> {{trans('wzoj.create_tag')}} </button>
  </form>
</div>

@endsection

@section ('scripts')
<script src="/include/js/jquery.nestable.js"></script>
<script>

var tags = {
	@foreach ($tags as $tag)
		{{$tag->id}}: {!! $tag->toJson() !!},
	@endforeach
};

function tagFormSetId(tagId){
	$('#tag-form').attr('action', '/admin/problem-tags/' + tagId);
	$('#showTagId').val(tagId);
	$('#name').val(tags[tagId].name);
	$('#aliases').val(tags[tagId].aliases);
	$('#reference_url').val(tags[tagId].reference_url);
	document.getElementById("name").focus();
}

function tagsExpandAll(){
	$('#tags-nestable').nestable('expandAll');
}

function tagsCollapseAll(){
	$('#tags-nestable').nestable('collapseAll');
}

$('.dd3-content').click(function(){
	tagFormSetId($(this).parent().data('id'));
});

$(document).ready(function() {
    if(window.location.hash != ''){
	tid = window.location.hash.substr(1)
	tagFormSetId(tid);
	$(window).scrollTop($('#tag-' + tid).offset().top - 60);
    }
    $('#tags-nestable').nestable({
	maxDepth: {{$tags->count()}},
    });

    $(window).scroll(function() {
	const buff_up = 80;
	const buff_bottom = 30;

	mintop = $('#tags-nestable').offset().top;
	coord = $('#rightbar').offset();
	height = $('#rightbar').height();
	windowtop = $(window).scrollTop();
	windowheight = $(window).height();

	if(windowheight > height + buff_up + buff_bottom){
	    coord.top = buff_up + windowtop;
	}else{
	    if(coord.top - windowtop > buff_up){
	        coord.top = buff_up + windowtop;
	    }else if(windowheight - coord.top + windowtop - height > buff_bottom){
	        coord.top = windowheight + windowtop - height - buff_bottom;
	    }
	}
	if(coord.top < mintop) coord.top = mintop;
	$('#rightbar').offset(coord);
    });

    $('#tags-nestable').on('change', function() {
	$('#save-changes-button').prop('disabled', false);
	$('#undo-changes-button').prop('disabled', false);
	$('#update-hierarchy-button').prop('disabled', true);
    });
});
</script>
@endsection
