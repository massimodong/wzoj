@extends ('layouts.master')

@section ('title')
{{trans('wzoj.login')}}
@endsection

@section ('content')
<form method="POST" action="/auth/login" class="form-signin">
{!! csrf_field() !!}
<h2 class="form-signin-heading">{{trans('wzoj.please_login')}}</h2>

<label for="name" class="sr-only">{{trans('wzoj.username')}}</label>
<input type="text" name="name" id="name" value="{{old('name')}}" class="form-control" placeholder="{{trans('wzoj.username')}}" required autofocus autocomplete="username">

<label for="password" class="sr-only">{{trans('wzoj.password')}}</label>
<input type="password" name="password" id="password" class="form-control" placeholder="{{trans('wzoj.password')}}" required autocomplete="current-password">


<button class="btn btn-lg btn-primary btn-block" type="submit">{{trans('wzoj.login')}}</button>
<div style="display: inline-block">
  <input type="checkbox" class="form-check-input" id="remember_me_check" name="remember">
  <label class="form-check-label" for="remember_me_check">{{trans('wzoj.remember_me')}}</label>
</div>
|
<a href='/auth/password/reset'>{{trans('wzoj.forgot_password')}}</a> <span class="text-muted">|</span> <a href="/auth/register">{{trans('wzoj.register')}}</a>
</form>
@endsection
