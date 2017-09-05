@extends ('admin.layout')

@section ('title')
{{trans('wzoj.problems')}}
@endsection

@section ('content')

<div class="col-xs-12 row">

<div class="col-xs-1">
    <form action='/admin/problems' method='POST'>
    {{csrf_field()}}
    <button type="submit" class="btn btn-default">+</button>
    </form>
</div>

<div class="col-xs-5">
    <form id='add_to_problemset_form' method='POST'>
        {{csrf_field()}}
    	<select id="pids" class="selectpicker" data-live-search="true" title="{{trans('wzoj.search_problemset')}}" required>
	@foreach (Auth::user()->manage_problemsets()->orderBy('id', 'asc')->get() as $problemset)
	    <option value="{{$problemset->id}}">{{$problemset->id}}-{{$problemset->name}}</option>
	@endforeach
	</select>
    	<button class="btn btn-primary" type="submit" onclick="problems_add_to_problemset();">
		{{trans('wzoj.add_to_problemset')}}
	</button>
    </form>
</div>

<div class="col-xs-6">
  <div class="dropdown">
    <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true    " aria-expanded="true">
    {{trans('wzoj.operations')}}
    <span class="caret"></span>
    </button>
    <ul class="dropdown-menu">
      <!-- <li><a href="#" onclick="problems_action('delete');" style="color: red"> {{trans('wzoj.delete_problems')}} </a></li> -->
    </ul>
  </div>
</div>

<table id="problems_table" class="table table-striped">
<thead>
    <th style="width: 1%"><input name="select_all" value="1" type="checkbox"></th>
    <th style="width: 5%">{{trans('wzoj.id')}}</th>
    <th>{{trans('wzoj.name')}}</th>
    <th style="width: 10%">{{trans('wzoj.type')}}</th>
    <th style="width: 5%">spj</th>
    <th style="width: 5%">{{trans('wzoj.source')}}</th>
    <th style="width: 10%">{{trans('wzoj.tags')}}</th>
    <th style="width: 10%">{{trans('wzoj.pass_rate')}}</th>
    <th style="width: 15%">{{trans('wzoj.problemsets')}}</th>
</thead>
<tbody>
@foreach ($problems as $problem)
    <tr>
    	<td></td>
	<td>{{$problem->id}}</td>
	<td>
	  <a href='/admin/problems/{{$problem->id}}'>{{$problem->name}}</a>
	  <a class="pull-right" href='/admin/problems/{{$problem->id}}/data'>[{{trans('wzoj.testdata')}}]</a>
	</td>
	<td>{{trans('wzoj.problem_type_'.$problem->type)}}</td>
	<td>{{$problem->spj?"Y":""}}</td>
	<td>{{$problem->source}}</td>
	<td>
	@foreach ($problem->tags as $tag)
          {{$tag->name}}
	@endforeach
	</td>
	<td>{{$problem->cntAc}}/{{$problem->cntSubmits}}</td>
	<td>
	@foreach ($problem->problemsets as $problemset)
		<a href="/s/{{$problemset->id}}/edit">{{$problemset->name}}</a>
	@endforeach
	@if (count($problem->problemsets) == 0)
		<span style="color:red">{{trans('wzoj.problem_not_in_any_problemsets')}}</span>
	@endif
	</td>
    </tr>
@endforeach
</tbody>
</table>

<form id="problems_form" action="/admin/problems" method="POST">
{{csrf_field()}}
</form>

</div>

@endsection

@section ('scripts')
<script>
function problems_action( action ){
        $("#problems_form").append('<input hidden name="_method" value="PUT">');
        $("#problems_form").append('<input hidden name="action" value="' + action + '">');
        //submit
        var submitInput = $("<input type='submit' />");
        $("#problems_form").append(submitInput);
        submitInput.trigger("click");
}
function problems_add_to_problemset(){
	$('#add_to_problemset_form').attr('action', '/s/' + $('#pids').find(':selected').attr('value') + '/problems');
	$.each(ids, function(index, id){
		$('#add_to_problemset_form').append(
			$('<input>')
			.attr('type', 'hidden')
			.attr('name', 'pids[]')
			.val(id)
		);
	});
}
var ids = [];
jQuery(document).ready(function($){
        createDatatableWithCheckboxs("problems_table", ids, "problems_form");
});
</script>
@endsection
