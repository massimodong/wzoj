@extends ('layouts.master')

@section ('title')
{{trans('wzoj.solutions')}}
@endsection

@section ('content')
<div class='col-lg-12'>

<div class='pull-right'>
[<a href='/solutions'>{{trans('wzoj.toppage')}}</a>]

@if ($prev_url <> '')
[<a href='{{$prev_url}}'>{{trans('wzoj.prevpage')}}</a>]
@endif

@if ($next_url <> '')
[<a href='{{$next_url}}'>{{trans('wzoj.nextpage')}}</a>]
@endif
</div>

<table class="table table-striped">
<thead>
    <tr>
    	<th style='width:5%'>{{trans('wzoj.id')}}</th>
	<th style='width:10%'>{{trans('wzoj.user')}}</th>
	<th style='width:15%'>{{trans('wzoj.problem')}}</th>
	<th style='width:12%'>{{trans('wzoj.status')}}</th>
	<th style='width:5%'>{{trans('wzoj.score')}}</th>
	<th style='width:5%'>{{trans('wzoj.time_used')}}</th>
	<th style='width:9%'>{{trans('wzoj.memory_used')}}</th>
	<th style='width:6%'>{{trans('wzoj.language')}}</th>
	<th style='width:7%'>{{trans('wzoj.code_length')}}</th>
	<th style='width:7%'>{{trans('wzoj.sim')}}</th>
	<th style='width:7%'>{{trans('wzoj.judger')}}</th>
	<th style='width:12%'>{{trans('wzoj.judged_at')}}</th>
    </tr>
</thead>
<tbody>
@foreach ($solutions as $solution)
    <tr id= 'tr-{{$solution->id}}' class='clickable-row' data-href='/solutions/{{$solution->id}}'>
        <td>{{$solution->id}}</td>
	<td>{{$solution->user->name}}</td>
	<td>{{$solution->problem->name}}</td>
	<td>
	@if ($solution->status > SL_RUNNING)
	    {{trans('wzoj.solution_status_'.$solution->status)}}
	@elseif ($solution->status == SL_RUNNING)
	    <div id='solution-{{$solution->id}}' class='judging-solution' data-id='{{$solution->id}}' data-waiting='1'></div>
	@else
	    <div id='solution-{{$solution->id}}' class='judging-solution' data-id='{{$solution->id}}' data-waiting='1'>
	    {{trans('wzoj.solution_status_'.$solution->status)}}</div>
	@endif
	</td>
	<td class='solution-score'>{{$solution->score}}</td>
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
	$.each($('.judging-solution'), function(key, value){
		var t = $('#'+value.id);
		animateJudging(t, fillTable);
	});
});
</script>
@endsection
