@extends ('layouts.master')

@section ('title')
{{trans('wzoj.problemsets')}}
@endsection

@section ('content')
<div class='col-xs-12'>

@can ('create',App\Problemset::class)
<form method='POST' action="/s">
    {{csrf_field()}}
    <button type="submit" class="btn btn-default">+</button>
</form>
@endcan

<table class="table table-striped">
<thead>
    <tr>
    	<th style="width:5%">{{trans('wzoj.id')}}</th>
	<th>{{trans('wzoj.name')}}</th>
	<th>{{trans('wzoj.type')}}</th>
	<th>{{trans('wzoj.contest_start_at')}}</th>
	<th>{{trans('wzoj.contest_end_at')}}</th>
	<th style="width:5%">{{trans('wzoj.public')}}</th>
    </tr>
</thead>
@foreach ($problemsets as $problemset)
    <tr>
    	<td>{{$problemset->id}}</td>
	<td>
	    <a href='/s/{{$problemset->id}}'> {{$problemset->name}} </a>
	    @can ('update',$problemset)
	    <a href='/s/{{$problemset->id}}/edit'> [{{trans('wzoj.edit')}}] </a>
	    @endcan
	    @if (strtotime($problemset->contest_start_at)<time())
	      <a href='/s/{{$problemset->id}}/ranklist'>[{{trans('wzoj.ranklist')}}]</a>
	    @endif
	</td>
	<td>{{trans('wzoj.problem_type_'.$problemset->type)}}</td>
	@if ($problemset->type != 'set')
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
