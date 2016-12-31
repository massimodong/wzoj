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
@foreach ($solutions as $solution)
    <tr class='clickable-row' data-href='/solutions/{{$solution->id}}'>
        <td>{{$solution->id}}</td>
	<td>{{$solution->user->name}}</td>
	<td>{{$solution->problem->name}}</td>
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
});
</script>
@endsection
