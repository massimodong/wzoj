@extends ('layouts.master')

@section ('title')
{{trans('wzoj.ranklist')}}
@endsection

@section ('head')
@parent
<!-- width for each problem -->
<style>
.sortable_list .sortable_list_cell{
  width: {{(100.0 - 35.0)/count($problems)}}%;
}
</style>
@endsection

@include ('layouts.contest_header')

@section ('content')
@if (Auth::check() && Auth::user()->has_role('admin'))
<div class="col-xs-12">
<a href="./ranklist_csv" class="pull-right">{{trans('wzoj.download')}}</a>
</div>
@endif

<div class="col-xs-1 row">
<ul class="sortable_list">
  <li class="col-xs-12"><div class="sortable_list_cell" style="width:100%"><span class="glyphicon glyphicon-king"></span></div></li>
</ul>
<hr>

<ul id="rank-indicator" class="sortable_list">
  @foreach ($table as $index => $row)
    <li class='col-xs-12'>
      <div class='rank_num sortable_list_cell' style='width:100%'>
        <small>{{$index + 1}}</small>
      </div>
    </li>
  @endforeach
</ul>
</div>

<div class='col-xs-11 row'>
<ul class='sortable_list'>
  <li class='col-xs-12'>
    <div class='sortable_list_cell' style='width:10%'><strong>{{trans('wzoj.user')}}</strong></div>
    <div class='sortable_list_cell' style='width:10%'><strong>{{trans('wzoj.fullname')}}</strong></div>
    <div class='sortable_list_cell' style='width:10%'><strong>{{trans('wzoj.class')}}</strong></div>
    <div class='sortable_list_cell' style='width:5%'><strong>{{trans('wzoj.score')}}</strong></div>
    @foreach ($problems as $problem)
	<div class='sortable_list_cell'><a href='/s/{{$problemset->id}}/{{$problem->id}}' title="{{$problem->name}}"><strong>{{$problem->name}}</strong></a></div>
    @endforeach
  </li>
</ul>
<hr>

<ul id="rank-table" class="sortable_list">
  @foreach ($table as $row)
    <li class='col-xs-12' id='user-{{$row->user->id}}' data-id='{{$row->user->id}}' data-score='{{$row->score}}'>
	<div class='rank-user sortable_list_cell' style='width:10%'><a href='/users/{{$row->user->id}}'>{{$row->user->name}}</a></div>
	<div class='rank-fullname sortable_list_cell' style='width:10%'>{{$row->user->fullname}}</div>
	<div class='rank-class sortable_list_cell' style='width:10%'>{{$row->user->class}}</div>
	<div class='rank-score sortable_list_cell' style='width:5%'>{{$row->score}}</div>

	@foreach ($problems as $problem)
	  @if (isset($row->problem_solutions[$problem->id]) && $solution = $row->problem_solutions[$problem->id])
	  <div class='problem-{{$problem->id}} sortable_list_cell' style='' data-score='{{$solution->score}}'>
	    <div id='solution-{{$solution->id}}' class='judging-solution' data-id='{{$solution->id}}' data-waiting='1' data-score='{{$solution->score}}'>
	      @if ($solution->ce)
	        {{trans('wzoj.compile_error')}}
	      @elseif ($solution->status == 4)
		{{$solution->score}}
	      @else
	        {{trans('wzoj.solution_status_'.$solution->status)}}
	      @endif
	      @if (!$contest_running && $row->problem_corrected_scores[$problem->id] >= 0)
		({{$row->problem_corrected_scores[$problem->id]}})
	      @endif
	    </div>
	  </div>
	  @else
	  <div class='problem-{{$problem->id}} sortable_list_cell' style=''>-</div>
	  @endif
	@endforeach
    </li>
  @endforeach
</ul>

</div>

<script>
//define template
var user_template = "<li class='col-xs-12'>" +
			"<div class='rank-user sortable_list_cell' style='width:10%'></div>" +
			"<div class='rank-fullname sortable_list_cell' style='width:10%'></div>" +
			"<div class='rank-class sortable_list_cell' style='width:10%'></div>" +
			"<div class='rank-score sortable_list_cell' style='width:5%'></div>";
			@foreach ($problems as $problem)
			user_template += "<div class='problem-{{$problem->id}} sortable_list_cell' style=''>-</div>"
			@endforeach
user_template += '</li>';

var indicator_template = "<li class='col-xs-12'><div class='rank_num sortable_list_cell' style='width:100%'></div></li>"

</script>

@endsection

@section ('scripts')
<script>
jQuery(document).ready(function($) {
	//initiate table
	ranklist_addRow.cnt = {{count($table)}};
	$('#rank-table').isotope({
		getSortData: {
			id: '[data-id] parseInt',
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

	ranklist_updateSolutions({{$problemset->id}});

	solutions_progress(ranklist_fillCell);
});
</script>
@endsection
