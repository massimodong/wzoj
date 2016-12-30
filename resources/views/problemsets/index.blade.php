@extends ('layouts.master')

@section ('title')
{{trans('wzoj.problemsets')}}
@endsection

@section ('content')
<div class='col-lg-12'>

@can ('create',App\Problemset::class)
<form method='POST'>
    {{csrf_field()}}
    <button type="submit" class="btn btn-default">+</button>
</form>
@endcan

<table class="table table-striped">
<thead>
    <tr>
    	<th>id</th>
	<th>name</th>
	<th>type</th>
	<th>contest_start_at</th>
	<th>contest_end_at</th>
	<th>public</th>
    </tr>
</thead>
@foreach ($problemsets as $problemset)
    <tr>
    	<td>{{$problemset->id}}</td>
	<td>
	    <a href='/s/{{$problemset->id}}'> {{$problemset->name}} </a>
	    @can ('update',$problemset)
	    <a href='/s/{{$problemset->id}}/edit'> [edit] </a>
	    @endcan
	</td>
	<td>{{$problemset->type}}</td>
	@if ($problemset->type === 'oi')
	<td>{{$problemset->contest_start_at}}</td>
	<td>{{$problemset->contest_end_at}}</td>
	@else
	<td></td><td></td>
	@endif
	<td>{{$problemset->public?"Y":"N"}}</td>
    </tr>
@endforeach
</table>

</div>
@endsection
