@extends ('layouts.master')

@section ('title')
{{trans('wzoj.solutions')}}
@endsection

@if (isset($problemset) && $problemset->type <> 'set')
    @include ('layouts.contest_header')
@endif

@section ('content')
<div class='col-lg-12'>

<div class='pull-right'>

@if ($url_limits <> '')
[<a href='/solutions?{{$url_limits}}'>{{trans('wzoj.toppage')}}</a>]
@else
[<a href='/solutions'>{{trans('wzoj.toppage')}}</a>]
@endif

@if ($prev_url <> '')
[<a href='{{$prev_url.$url_limits}}'>{{trans('wzoj.prevpage')}}</a>]
@endif

@if ($next_url <> '')
[<a href='{{$next_url.$url_limits}}'>{{trans('wzoj.nextpage')}}</a>]
@endif
</div>

<table class="table table-striped">
<thead>
    <tr>
    	<th style='width:5%'>{{trans('wzoj.id')}}</th>
	<th style='width:8%'>{{trans('wzoj.user')}}</th>
	<th style='width:15%'>{{trans('wzoj.problem')}}</th>
	<th style='width:12%'>{{trans('wzoj.status')}}</th>
	<th style='width:7%'>{{trans('wzoj.score')}}</th>
	<th style='width:5%'>{{trans('wzoj.time_used')}}</th>
	<th style='width:9%'>{{trans('wzoj.memory_used')}}</th>
	<th style='width:6%'>{{trans('wzoj.language')}}</th>
	<th style='width:7%'>{{trans('wzoj.code_length')}}</th>
	<th style='width:7%'>{{trans('wzoj.sim')}}</th>
	<th style='width:7%'>{{trans('wzoj.judger')}}</th>
	<th style='width:12%'>{{trans('wzoj.judged_at')}}</th>
    </tr>
</thead>
<tbody id='solutions-tbody'>
@foreach ($solutions as $solution)
    <tr id= 'tr-{{$solution->id}}' class='clickable-row' data-href='/solutions/{{$solution->id}}'>
        <td>{{$solution->id}}</td>
	<td>{{$solution->user->name}}</td>
	<td>{{$solution->problem->name}}</td>
	<td>
	@if ($solution->status == SL_RUNNING)
	    <div id='solution-{{$solution->id}}' class='judging-solution' data-id='{{$solution->id}}' data-waiting='1'></div>
	@else
	    <div id='solution-{{$solution->id}}' class='judging-solution' data-id='{{$solution->id}}' data-waiting='1'>
	    {{trans('wzoj.solution_status_'.$solution->status)}}</div>
	@endif
	</td>
	@if ($solution->ce)
	<td class='solution-score'>{{trans('wzoj.compile_error')}}</td>
	@else
	<td class='solution-score'>{{$solution->score}}</td>
	@endif
	<td class='solution-timeused'>{{$solution->time_used}}ms</td>
	<td class='solution-memoryused'>{{sprintf('%.2f', $solution->memory_used / 1024 / 1024)}}MB</td>
	<td>
	    @if ($solution->language == 0)
		C
	    @endif
	    @if ($solution->language == 1)
		C++
	    @endif
	    @if ($solution->language == 2)
		Pascal
	    @endif
	</td>
	<td>{{$solution->code_length}}B</td>
	<td>todo</td>
	<td>todo</td>
	<td class='solution-judgedat'>{{$solution->judged_at}}</td>
    </tr>
@endforeach
</tbody>
</table>

</div>
@endsection

@section ('scripts')
<script>
jQuery(document).ready(function($) {
	$(".clickable-row").click(function() {
		window.document.location = $(this).data("href");
	});
	solutions_update({{$last_solution_id}});
	updatePendings(fillTable);
});
</script>
@endsection
