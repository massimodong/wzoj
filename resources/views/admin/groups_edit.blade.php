@extends ('admin.layout')

@section ('title')
group {{$group->name}}
@endsection

@section ('content')

<form method='POST' id='group_form'>
{{csrf_field()}}
{{method_field('PUT')}}
<input type='text' name='name' value='{{$group->name}}'>
<button>change</button>
</form>

<p>
<h4>Users:</h4>
@foreach ($group->users as $user)
	<p>{{$user->name}}</p>
	<form method='POST' action='/admin/groups/{{$group->id}}/{{$user->id}}'>
	{{csrf_field()}}
	{{method_field('delete')}}
	<button type='submit'>delete</button>
	</form>
@endforeach
</p>

<hr />
<form method='POST'>
{{csrf_field()}}
Add member:<input type='text' name='user_id'>
<button type='submit'>submit</button>
</form>

<form method='POST'>
{{csrf_field()}}
{{method_field('DELETE')}}
<button>delete</button>
</form>

@endsection
