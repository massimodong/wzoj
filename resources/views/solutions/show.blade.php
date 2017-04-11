@extends ('layouts.master')

@section ('title')
{{trans('wzoj.solution')}}
@endsection

@if ($solution->problemset_id > 0 && $problemset->type <> 'set')
@include ('layouts.contest_header')
@endif

@section ('content')
<div class="col-xs-12">

<table class="table table-striped">
<thead>
    <tr>
    	<th style='width:6%'>{{trans('wzoj.id')}}</th>
	<th style='width:9%'>{{trans('wzoj.user')}}</th>
	<th style='width:15%'>{{trans('wzoj.problem')}}</th>
	<th style='width:12%'>{{trans('wzoj.status')}}</th>
	<th style='width:8%'>{{trans('wzoj.score')}}</th>
	<th style='width:6%'>{{trans('wzoj.time_used')}}</th>
	<th style='width:10%'>{{trans('wzoj.memory_used')}}</th>
	<th style='width:7%'>{{trans('wzoj.language')}}</th>
	<th style='width:7%'>{{trans('wzoj.code_length')}}</th>
	<th style='width:8%'>{{trans('wzoj.judger')}}</th>
	<th style='width:12%'>{{trans('wzoj.submitted_at')}}</th>
    </tr>
</thead>
<tbody>
    <tr>
        <td>{{$solution->id}}</td>
	<td><a href='/users/{{$solution->user->id}}'>{{$solution->user->name}}</a></td>
	@if ($solution->problemset_id > 0)
	    @if ($problemset->public || Gate::allows('view', $problemset))
	        <td><a href='/s/{{$solution->problemset->id}}/{{$solution->problem->id}}'>{{$solution->problem->name}}</a></td>
	    @else
	        <td>{{$solution->problem->name}}</td>
	    @endif
	@else
	    @if (Auth::check() && Auth::user()->has_role('admin'))
	        <td><a href='/admin/problems/{{$solution->problem->id}}'>{{$solution->problem->name}}</a></td>
	    @else
	        <td>{{$solution->problem->name}}</td>
	    @endif
	@endif
	<td>
	  <div id='solution-{{$solution->id}}' class='judging-solution' data-id='{{$solution->id}}' data-waiting='1'>
	  {{trans('wzoj.solution_status_'.$solution->status)}}</div>
	</td>

	<td>{{$solution->score}}</td>
	<td>{{$solution->time_used}}ms</td>
	<td>{{sprintf('%.2f', $solution->memory_used / 1024 / 1024)}}MB</td>
	<td>{{trans('wzoj.programing_language_'.$solution->language)}}</td>
	<td>{{$solution->code_length}}B</td>
	<td>{{$solution->judger?$solution->judger->name:""}}</td>
	<td>{{$solution->created_at}}</td>
    </tr>
</tbody>
</table>
<hr>

@if ($solution->problem->type <> 3)
  @can ('view_code', $solution)
  <h3>{{trans('wzoj.code')}}</h3>
  <button id='code_button' type="button" class="btn btn-xs btn-default" onclick="showOrHideCode();return false;" >—</button>
  <pre id='code_pre' style="display:block;"><code class="language-{{trans('wzoj.programing_lang_short_'.$solution->language)}}">{{$solution->code}}</code></pre>
  @endcan
@endif

@if ($solution->status == SL_JUDGED)

	@if (isset($solution->ce))
		<h3>{{trans('wzoj.compile_error')}}</h3>
		@can ('view_code', $solution)
		<pre>{{$solution->ce}}</pre>
		@endcan
		<hr>
	@else

		<h3>{{trans('wzoj.testcases')}}</h3>
		<table class="table table-striped">
		<thead>
		    <tr>
			<th>{{trans('wzoj.name')}}</th>
			<th>{{trans('wzoj.score')}}</th>
			<th>{{trans('wzoj.time_used')}}</th>
			<th>{{trans('wzoj.memory_used')}}</th>
			<th>{{trans('wzoj.verdict')}}</th>
			<th>{{trans('wzoj.checklog')}}</th>
 		   </tr>
		</thead>
		<tbody>
		@foreach ($solution->testcases as $testcase)
		    <tr>
		    	@if ($solution->problem->type == 3)
		    	  @can ('view_code', $solution)
			    <td><a href='#' title="{{trans('wzoj.download_answerfile')}}">{{$testcase->filename}}</a></td>
			  @else
			    <td>{{$testcase->filename}}</td>
			  @endcan
			@else
			    <td>{{$testcase->filename}}</td>
			@endif
			<td>{{$testcase->score}}</td>
			<td>{{$testcase->time_used}}ms</td>
			<td>{{sprintf('%.2f', $testcase->memory_used / 1024 / 1024)}}MB</td>
			<td>{{$testcase->verdict}}</td>
			<td>{{$testcase->checklog}}</td>
		    </tr>
		@endforeach
		</tbody>
	</table>

	@endif

@endif

</div>
@endsection

@section ('scripts')
<script>
$( document ).ready(function() {
	showOrHideCode();

	@if ($solution->status < 4)
	animateJudging($('#solution-{{$solution->id}}') ,function(s){
		fillTable(s);
		location.reload();
			});
	@endif
});
</script>
@endsection
