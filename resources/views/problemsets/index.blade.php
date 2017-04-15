@extends ('layouts.master')

@section ('title')
{{trans('wzoj.problemsets')}}
@endsection

@section ('content')
<div class='col-xs-12'>

@can ('create',App\Problemset::class)
<form method='POST'>
    {{csrf_field()}}
    <button type="submit" class="btn btn-default">+</button>
</form>
@endcan

<h3>{{trans('wzoj.search_problem')}}</h3>

<form action="/problem-search" method="GET" class="form-horizontal">
  <div class="form-group">
    <label class="control-label col-xs-2" for="name">{{trans('wzoj.problem_name')}}:</label>
    <div class="col-xs-3">
      <input type="text" class="form-control" name="name" id="name" placeholder="{{trans('wzoj.problem_name')}}">
    </div>
    <div class="col-xs-7">
      <span class="help-block">{{trans('wzoj.msg_problem_search_name_helper')}}</span>
    </div>
  </div>
  <div class="form-group">
    <label class="control-label col-xs-2" for="name">{{trans('wzoj.tags')}}:</label>
    <div class="col-xs-3">
      <select id="tag-select" class="selectpicker" data-live-search="true" data-width="70%">
        <option style="display:none"></option>
        @foreach ($tags as $tag)
	  <option data-tokens="{{$tag->aliases}}" value="{{$tag->id}}">{{$tag->name}}</option>
	@endforeach
      </select>
      <button type="submit" class="btn btn-default" onclick="searchAddTag();return false;">{{trans('wzoj.add')}}</button>
    </div>
    <div id="tags" class="col-xs-7">
    </div>
  </div>
  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">{{trans('wzoj.search')}}</button>
    </div>
  </div>
</form>

<hr>

<h3>{{trans('wzoj.problemsets')}}</h3>
<table class="table table-striped">
<thead>
    <tr>
    	<th style="width:5%">{{trans('wzoj.id')}}</th>
	<th>{{trans('wzoj.name')}}</th>
    </tr>
</thead>
@foreach ($problemsets as $problemset)
    <tr>
    	<td>{{$problemset->id}}</td>
	<td>
	    <a href='/s/{{$problemset->id}}'> {{$problemset->name}} </a>
	    @can ('update',$problemset)
	    <a href='/s/{{$problemset->id}}/edit'> [{{trans('wzoj.edit')}}] </a>
	    @endcan
	</td>
    </tr>
@endforeach
</table>

</div>
@endsection

@section ('scripts')
<script>
var tag_names = ['',
    @foreach ($tags as $tag)
	'{{$tag->name}}',
    @endforeach
];
function searchAddTag(){
	var cur_tag_id = $('#tag-select').val();
	if(cur_tag_id <= 0) return;

	if($('#tag-' + cur_tag_id).length) return;

	var new_tag = $("<span id='tag-" + cur_tag_id + "'></span>");
	new_tag.append("<span onclick='searchRemoveTag(" + cur_tag_id + ")' class='label label-default clickable'>" + tag_names[cur_tag_id] + "</span>");
	new_tag.append("<input name='tags[]' value='" + cur_tag_id + "' hidden> ");

	$('#tags').append(new_tag);
}
function searchRemoveTag(tag_id){
	$('#tag-' + tag_id).remove();
}
</script>
@endsection
