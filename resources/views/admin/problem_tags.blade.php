@extends ('admin.layout')

@section ('title')
{{trans('wzoj.tags')}}
@endsection (title')

@section ('head')
@parent
<link href="/include/css/dd-nestable.css" rel="stylesheet">
@endsection

@section ('content')

<form id="tag-form" method="POST">
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
  <button type="submit" class="btn btn-primary" id="update-hierarchy-button"> {{trans('wzoj.update')}} </button>
</form>

<div class="buffer-sm"></div>

<form action="/admin/problem-tags/hierarchy" method="POST">
  {{csrf_field()}}
  {{method_field('PUT')}}
  <input hidden name="tags" id="tags-hierarchy">
  <button type="submit" class="btn btn-primary" id="save-changes-button" disabled onclick="$('#tags-hierarchy').val(JSON.stringify($('#tags-nestable').nestable('serialize'),null,2))">
  {{trans('wzoj.save_changes')}} </button>
  <button type="submit" class="btn btn-primary" id="undo-changes-button" disabled onclick="location.reload();return false;"> {{trans('wzoj.undo_changes')}} </button>
  <button type="submit" class="btn btn-primary" onclick="tagsExpandAll();return false;"> {{trans('wzoj.expand_all')}} </button>
  <button type="submit" class="btn btn-primary" onclick="tagsCollapseAll();return false;"> {{trans('wzoj.collapse_all')}} </button>
</form>

<div class="buffer-sm"></div>

<form action="/admin/problem-tags" method="POST">
  {{csrf_field()}}
  <button type="submit" class="btn btn-primary"> {{trans('wzoj.create_tag')}} </button>
</form>

<div class="dd-nestable" id="tags-nestable">
  @include ('partials.problem_tags_recursive', ['tags' => $tags->filter(function($value){
                          return $value->parent_id == 0;
                        })->sortBy('index')])
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

    $('#tags-nestable').on('change', function() {
        $('#save-changes-button').prop('disabled', false);
        $('#undo-changes-button').prop('disabled', false);
        $('#update-hierarchy-button').prop('disabled', true);
    });
});
</script>
@endsection
