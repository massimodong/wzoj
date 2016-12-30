@extends ('admin.layout')

@section ('title')
{{trans('wzoj.problems')}}
@endsection

@section ('content')

<div class="col-lg-12">

<div class="pull-right">
    [<a href='/admin/problems'>{{trans('wzoj.toppage')}}</a>]
@if ($prevpage_url <> '')
    [<a href='{{$prevpage_url}}'>{{trans('wzoj.prevpage')}}</a>]
@endif

@if ($nextpage_url <> '')
    [<a href='{{$nextpage_url}}'>{{trans('wzoj.nextpage')}}</a>]
@endif
    [<a href='{{$bottompage_url}}'>{{trans('wzoj.bottompage')}}</a>]
</div>

<div>
    <form action='/admin/problems' method='POST'>
    {{csrf_field()}}
    <button type="submit" class="btn btn-default">+</button>
    </form>
</div>

<table class="table table-striped">
<thead>
    <th>id</th>
    <th>name</th>
    <th>type</th>
    <th>spj</th>
    <th>source</th>
</thead>
<tbody>
@foreach ($problems as $problem)
    <tr>
	<td>{{$problem->id}}</td>
	<td><a href='/admin/problems/{{$problem->id}}'>{{$problem->name}}</a></td>
	<td>{{trans('wzoj.problem_type_'.$problem->type)}}</td>
	<td>{{$problem->spj?"Y":""}}</td>
	<td>{{$problem->source}}</td>
    </tr>
@endforeach
</tbody>
</table>

</div>

@endsection
