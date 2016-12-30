@extends ('layouts.master')

@section ('title')
{{trans('wzoj.solutions')}}
@endsection

@section ('content')
<div class='col-lg-12'>

<table class="table table-striped">
<thead>
    <tr>
    	<th>id</th>
	<th>user</th>
	<th>problem</th>
	<th>status</th>
	<th>score</th>
	<th>time used</th>
	<th>memory used</th>
	<th>language</th>
	<th>code length</th>
	<th>sim</th>
	<th>judger</th>
	<th>judged_at</th>
    </tr>
</thead>
<tbody>
@foreach ($solutions as $solution)
    <tr>
        <td>{{$solution->id}}</td>
	<td>{{$solution->user->name}}</td>
	<td>{{$solution->problem->name}}</td>
	<td>{{$solution->status}}</td>
	<td>{{$solution->score}}</td>
	<td>{{$solution->time_used}}</td>
	<td>{{$solution->memory_used}}</td>
	<td>{{$solution->language}}</td>
	<td>{{$solution->code_length}}</td>
	<td>todo</td>
	<td>todo</td>
	<td>todo</td>
    </tr>
@endforeach
</tbody>
</table>

</div>
@endsection
