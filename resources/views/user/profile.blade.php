@extends ('layouts.master')

@section ('title')
{{$user->fullname}}
@endsection

@section ('content')

<p>username:{{$user->name}}</p>
<p>email:{{$user->email}}</p>

<form method='POST'>
{{csrf_field()}}

<p>
fullname:
@can ('change_fullname' , $user)
<input type='text' name='fullname' value="{{$user->fullname}}">
@else
{{$user->fullname}}
@endcan

@can ('change_lock' , $user)
<input type="checkbox" name="fullname_lock" value="1" {{$user->fullname_lock?"checked":""}}>lock<br>
@endcan
</p>

<p>
class:
@can ('change_class' , $user)
<input type="text" name='class' value="{{$user->class}}">
@else
{{$user->class}}
@endcan

@can ('change_lock' , $user)
<input type="checkbox" name="class_lock" value="1" {{$user->class_lock?"checked":""}}>lock<br>
@endcan
</p>

<button>submit</button>

</form>

@endsection
