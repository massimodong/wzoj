@extends ('layouts.master')

@section ('title')
{{trans('wzoj.ranklist')}}
@endsection

@section ('head')
@parent
<!-- width for each problem -->
<style>
.sortable_list .sortable_list_cell{
  width: {{(100.0 - 20.0)/count($problems)}}%;
}
</style>
@endsection

@include ('layouts.contest_header')

@section ('content')

<ul class='sortable_list'>
  <li class='col-lg-12' style='height:40px;'>
    <div class='sortable_list_cell' style='width:5%'>{{trans('wzoj.user')}}</div>
    <div class='sortable_list_cell' style='width:10%'>{{trans('wzoj.class')}}</div>
    <div class='sortable_list_cell' style='width:5%'>{{trans('wzoj.score')}}</div>
    @foreach ($problems as $problem)
	<div class='sortable_list_cell'><a href='/s/{{$problemset->id}}/{{$problem->id}}'>{{$problem->name}}</a></div>
    @endforeach
  </li>
</ul>
<hr>

<ul id="rank-table" class="sortable_list">
</ul>

<script>
//define template
var user_template = "<li class='col-lg-12' style='height:40px;'>" +
			"<div class='rank-user sortable_list_cell' style='width:5%'></div>" +
			"<div class='rank-class sortable_list_cell' style='width:10%'></div>" +
			"<div class='rank-score sortable_list_cell' style='width:5%'></div>";
			@foreach ($problems as $problem)
			user_template += "<div class='problem-{{$problem->id}} sortable_list_cell'></div>"
			@endforeach
user_template += '</li>';

//init solutions
var init_solutions = {!! json_encode($solutions) !!};
</script>

@endsection

@section ('scripts')
<script>
jQuery(document).ready(function($) {
	//initiate table
	$('#rank-table').isotope({
		getSortData: {
			id: '[id]',
			score: '.rank-score parseInt',
			penalty: function(itemElem) {
				if(typeof $(itemElem).data('penalty') == 'undefined'){
					return 0;
				}
				return $(itemElem).data('penalty');
			}
		},
		sortAscending: {
			id: true,
			score: false,
			penalty: true
		},
		sortBy: ['score', 'penalty', 'id']
	});

	$.each(init_solutions, function(key, solution){
		ranklist_addSolution(solution);
	});

	ranklist_updateSolutions({{$problemset->id}}, {{$last_solution_id}});
	updatePendings(ranklist_fillCell);
});
</script>
@endsection
