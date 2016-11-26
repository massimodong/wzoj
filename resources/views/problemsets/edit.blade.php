@extends ('layouts.master')

@section ('title')
set {{$problemset->name}}
@endsection

@section ('content')

<div>
<h3>description:</h3>
{{$problemset->description}}
</div>

<div>
<h3>problems</h3>
@foreach ($problems as $problem)
	<p>
	<a href='/s/{{$problemset->id}}/{{$problem->id}}'>{{$problem->pivot->index}}:{{$problem->name}}</a>
	<form action='/s/{{$problemset->id}}/{{$problem->id}}' method='POST'>
	{{csrf_field()}}
	{{method_field('PUT')}}
	<input name='newindex'><button>move to</button>
	</form>

	<form action='/s/{{$problemset->id}}/{{$problem->id}}' method='POST'>
	{{csrf_field()}}
	{{method_field('DELETE')}}
	<button>delete</button>
	</form>
	</p>
@endforeach
</div>

<form action='/s/{{$problemset->id}}' method='POST'>
{{csrf_field()}}
<input name='pid'>
<button>new problem</button>
</form>

@endsection
