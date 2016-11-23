@extends ('layouts.master')

@section ('title')
Login
@endsection

@section ('content')
<form method="POST" action="/auth/login">
{!! csrf_field() !!}

<div>
username
<input type="text" name="name" value="{{ old('name') }}">
</div>

<div>
Password
<input type="password" name="password" id="password">
</div>

<div>
<input type="checkbox" name="remember"> Remember Me
</div>

<div>
<button type="submit">Login</button>
</div>
</form>
@endsection
