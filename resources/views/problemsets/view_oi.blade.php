@extends ('layouts.master')

@section ('title')
set {{$problemset->name}}
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
