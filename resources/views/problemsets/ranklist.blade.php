@extends ('layouts.master')

@section ('title')
{{trans('wzoj.ranklist')}}
@endsection

@section ('head')
@parent
<!-- width for each problem -->
<style>
.sortable_list .sortable_list_cell{
  width: {{(100.0 - 25.0)/count($problems)}}%;
}
</style>
@endsection

@include ('layouts.contest_header')

@section ('content')

<div class="col-xs-1 row">
<ul class="sortable_list">
  <li class="col-xs-12"><div class="sortable_list_cell" style="width:100%"><span class="glyphicon glyphicon-king"></span></div></li>
</ul>
<hr>

<ul id="rank-indicator" class="sortable_list">
</ul>
</div>

<div class='col-xs-11 row'>
<ul class='sortable_list'>
  <li class='col-xs-12'>
    <div class='sortable_list_cell' style='width:10%'><strong>{{trans('wzoj.user')}}</strong></div>
    <div class='sortable_list_cell' style='width:10%'><strong>{{trans('wzoj.class')}}</strong></div>
    <div class='sortable_list_cell' style='width:5%'><strong>{{trans('wzoj.score')}}</strong></div>
    @foreach ($problems as $problem)
	<div class='sortable_list_cell'><a href='/s/{{$problemset->id}}/{{$problem->id}}'><strong>{{$problem->name}}</strong></a></div>
    @endforeach
  </li>
</ul>
<hr>

<ul id="rank-table" class="sortable_list">
</ul>

</div>

<script>
//define template
var user_template = "<li class='col-xs-12'>" +
			"<div class='rank-user sortable_list_cell' style='width:10%'></div>" +
			"<div class='rank-class sortable_list_cell' style='width:10%'></div>" +
			"<div class='rank-score sortable_list_cell' style='width:5%'></div>";
			@foreach ($problems as $problem)
			user_template += "<div class='problem-{{$problem->id}} sortable_list_cell' style=''>-</div>"
			@endforeach
user_template += '</li>';

var indicator_template = "<li class='col-xs-12'><div class='rank_num sortable_list_cell' style='width:100%'></div></li>"

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

	@foreach ($solutions_judging as $solution)
		animateJudging($('#solution-{{$solution->id}}'), fillTable);
	@endforeach

	updatePendings(ranklist_fillCell);
});
</script>
@endsection
