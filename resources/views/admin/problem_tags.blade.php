@extends ('admin.layout')

@section ('title')
{{trans('wzoj.tags')}}
@endsection (title')

@section ('head')
@parent
<link href="/include/css/dd-nestable.css" rel="stylesheet">
@endsection

@section ('content')

<div class="dd-nestable col-xs-6" id="tags-nestable">
  @include ('admin.problem_tags_recursive', ['tags' => $tags->filter(function($value){
			    return $value->parent_id == 0;
			  })])
</div>

<div class="col-xs-4">
  <form id="tag-form" method="POST">
    {{csrf_field()}}
    {{method_field('PUT')}}

    <div class="form-group">
      <label for="showTagId"> {{trans('wzoj.id')}} </label>
      <input type="text" class="form-control" id="showTagId" size="80" disabled>
    </div>
    <div class="form-group">
      <label for="name"> {{trans('wzoj.name')}} </label>
      <input type="text" class="form-control" id="name" name="name" size="80">
    </div>
    <div class="form-group">
      <label for="aliases"> {{trans('wzoj.aliases')}} </label>
      <input type="text" class="form-control" id="aliases" name="aliases" size="80">
    </div>
    <div class="form-group">
      <label for="reference_url"> {{trans('wzoj.reference_url')}} </label>
      <input type="text" class="form-control" id="reference_url" name="reference_url" size="80">
    </div>
    <button type="submit" class="btn btn-default"> {{trans('wzoj.update')}} </button>
  </form>
</div>

<div class="col-xs-2">
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
});
</script>
@endsection
