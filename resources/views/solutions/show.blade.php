@extends ('layouts.master')

@section ('title')
{{trans('wzoj.solution')}}
@endsection

@section ('content')
<div class="col-lg-12">

<table class="table table-striped">
<thead>
    <tr>
    	<th>{{trans('wzoj.id')}}</th>
	<th>{{trans('wzoj.user')}}</th>
	<th>{{trans('wzoj.problem')}}</th>
	<th>{{trans('wzoj.status')}}</th>
	<th>{{trans('wzoj.score')}}</th>
	<th>{{trans('wzoj.time_used')}}</th>
	<th>{{trans('wzoj.memory_used')}}</th>
	<th>{{trans('wzoj.language')}}</th>
	<th>{{trans('wzoj.code_length')}}</th>
	<th>{{trans('wzoj.sim')}}</th>
	<th>{{trans('wzoj.judger')}}</th>
	<th>{{trans('wzoj.judged_at')}}</th>
    </tr>
</thead>
<tbody>
    <tr>
        <td>{{$solution->id}}</td>
	<td><a href='/users/{{$solution->user->id}}'>{{$solution->user->name}}</a></td>
	@if ($solution->problemset_id > 0)
	    <td><a href='/s/{{$solution->problemset->id}}/{{$solution->problem->id}}'>{{$solution->problem->name}}</a></td>
	@else
	    <td><a href='/admin/problems/{{$solution->problem->id}}'>{{$solution->problem->name}}</a></td>
	@endif
	<td>{{trans('wzoj.solution_status_'.$solution->status)}}</td>
	<td>{{$solution->score}}</td>
	<td>{{$solution->time_used}}ms</td>
	<td>{{sprintf('%.2f', $solution->memory_used / 1024 / 1024)}}MB</td>
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
	<td>{{$solution->judged_at}}</td>
    </tr>
</tbody>
</table>
<hr>

@can ('view_code', $solution)
<h3>{{trans('wzoj.code')}}</h3>
<button id='code_button' type="button" class="btn btn-xs btn-default" onclick="showOrHideCode();return false;" >—</button>
<pre id='code_pre' class="brush: 

@if ($solution->lang <= 1)
	c++
@else
	pascal
@endif

" style="display:block;">{{$solution->code}}</pre>
@endcan

@if (isset($solution->ce))
	<h3>{{trans('wzoj.compile_error')}}</h3>
	<pre>{{$solution->ce}}</pre>
	<hr>
@endif

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
@foreach ($solution->testcases as $testcase)
    <tr>
	<td>{{$testcase->filename}}</td>
	<td>{{$testcase->score}}</td>
	<td>{{$testcase->time_used}}ms</td>
	<td>{{sprintf('%.2f', $testcase->memory_used / 1024 / 1024)}}MB</td>
	<td>{{$testcase->verdict}}</td>
	<td>{{$testcase->checklog}}</td>
    </tr>
@endforeach
<tbody>
</tbody>
</table>

</div>
@endsection

@section ('scripts')
<script>
$( document ).ready(function() {
	showOrHideCode();
});
</script>
@endsection
