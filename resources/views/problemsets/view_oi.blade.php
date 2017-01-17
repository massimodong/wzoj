@extends ('layouts.master')

@section ('title')
set {{$problemset->name}}
@endsection

@section ('sidebar')
<li id='home_sidebar'><a href="/"> {{trans('wzoj.home')}} </a></li>
<li id='problems_sidebar'><a href="/s/{{$problemset->id}}"> {{trans('wzoj.problem')}} </a></li>
<li id='solutions_sidebar'><a href="/solutions?problemset_id={{$problemset->id}}"> {{trans('wzoj.solutions')}} </a></li>
@endsection

@section ('content')

<h3>(oi)</h3>
<div>
<h3>description:</h3>
{{$problemset->description}}
</div>

<div>
<h3>problems</h3>
@foreach ($problems as $problem)
	<p><a href='/s/{{$problemset->id}}/{{$problem->id}}'>{{$problem->pivot->index}}:{{$problem->name}}</a></p>
@endforeach
</div>

@endsection
