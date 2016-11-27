@extends ('layouts.master')

@section ('title')
set {{$problemset->name}}
@endsection

@section ('content')

<div>
<form action='/s/{{$problemset->id}}' method='POST'>
{{csrf_field()}}
{{method_field('PUT')}}

<p>name:<input name='name' value='{{$problemset->name}}'></p>
<p>type:<input name='type' value='{{$problemset->type}}'></p>
<p><input type='checkbox' name='public' value='1' {{$problemset->public?"checked":""}}>public</p>
<p><textarea name='description'>{{$problemset->description}}</textarea></p>

<button>submit</button>
</form>
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

<form action='/s/{{$problemset->id}}' method='POST'>
{{csrf_field()}}
{{method_field('DELETE')}}
<button>delete problemset</button>
</form>

<hr>
<h3>groups</h3>
@foreach ($problemset->groups as $group)
{{$group->name}}
@endforeach
@endsection
