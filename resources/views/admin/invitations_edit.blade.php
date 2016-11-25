@extends ('admin.layout')

@section ('title')
{{$invitation->description}} invitation
@endsection

@section ('content')

<form method='POST'>
{{csrf_field()}}
{{method_field('PUT')}}
Description:<br>
<textarea name='description'>{{$invitation->description}}</textarea><br>

lock fullname:<br>
<input type='text' name='fullname' value='{{$invitation->fullname}}'><br>

lock class:<br>
<input type='text' name='class' value='{{$invitation->class}}'><br>

token:<br>
<input type='text' name='token' value='{{$invitation->token}}'><br>

remaining:<br>
<input type='text' name='remaining' value='{{$invitation->remaining}}'><br>

<input type='checkbox' name='private' value='1' {{$invitation->private?"checked":""}}>private<br>

<button>submit</button>
</form>


<h3>Add in Groups:</h3>
@foreach ($invitation->groups as $group)
	<p>{{$group->name}}</p>
@endforeach

@endsection
