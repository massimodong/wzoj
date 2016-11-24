@extends ('admin.layout')

@section ('title')
group {{$group->name}}
@endsection

@section ('content')

<p>
<h4>Group Name:</h4>{{$group->name}}
</p>

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

@endsection
