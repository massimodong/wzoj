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
  @include ('admin.problem_tags_recursive', ['tags' => $tags])
</div>

<div class="col-xs-6">
</div>

@endsection

@section ('scripts')
<script src="/include/js/jquery.nestable.js"></script>
<script>
$(document).ready(function() {
    $('#tags-nestable').nestable({
	maxDepth: {{$tags->count()}},
    });
});
</script>
@endsection
