@extends ('layouts.master')

@section ('title')
{{trans('wzoj.login')}}
@endsection

@section ('content')
<form method="POST" action="/auth/login" class="form-signin">
{!! csrf_field() !!}
<h2 class="form-signin-heading">{{trans('wzoj.please_login')}}</h2>

<label for="name" class="sr-only">{{trans('wzoj.username')}}</label>
<input type="text" name="name" id="name" value="{{old('name')}}" class="form-control" placeholder="{{trans('wzoj.username')}}" required autofocus>

<label for="password" class="sr-only">{{trans('wzoj.password')}}</label>
<input type="password" name="password" id="password" class="form-control" placeholder="{{trans('wzoj.password')}}" required>

<input type="checkbox" name="remember" checked style="display: none">

<button class="btn btn-lg btn-primary btn-block" type="submit">{{trans('wzoj.login')}}</button>
<a href='/password/email'>{{trans('wzoj.forgot_password')}}</a> <span class="text-muted">|</span> <a href="/auth/register">{{trans('wzoj.register')}}</a>
</form>
@endsection
