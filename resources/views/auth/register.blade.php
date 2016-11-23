@extends ('layouts.master')

@section ('title')
Register
@endsection

@section ('content')
<form method="POST" action="/auth/register?token={{$invitation->token}}">
{!! csrf_field() !!}

<div>
Name
<input type="text" name="name" value="{{ old('name') }}">
</div>


<div>
Fullname
<input type="text" name="fullname" 

@if ($invitation->fullname <> '')
	value="{{$invitation->fullname}}" disabled
@else
	value="{{ old('fullname') }}"
@endif
>
</div>

<div>
Class
<input type="text" name="class"

@if ($invitation->class <> '')
	value="{{$invitation->class}}" disabled
@else
	value="{{ old('class')}}"
@endif
>
</div>

<div>
Email
<input type="email" name="email" value="{{ old('email') }}">
</div>

<div>
Password
<input type="password" name="password">
</div>

<div>
Confirm Password
<input type="password" name="password_confirmation">
</div>

<div>
<button type="submit">Register</button>
</div>
</form>
@endsection
