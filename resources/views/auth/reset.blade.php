@extends('layouts.master')

@section('title')
{{trans('wzoj.reset_password')}}
@endsection

@section('content')

<div class="container">
<div class="row">
<div class="col-md-12">
<form method="POST" action="/password/reset">
	{!! csrf_field() !!}
	<input type="hidden" name="token" value="{{ $token }}">

	<div class="col-md-offset-4 col-md-4">
	
	<div class="form-group">
		<label for="inputEmail">Email</label>
		<input type="email" class="form-control" id="inputEmail"
		name="email" placeholder="Email" value="" required autofocus>
	</div>
	
	<div class="form-group">
		<label for="inputPassword">{{trans('wzoj.password')}}</label>
		<input type="password" class="form-control" id="inputPassword" 
		name="password" placeholder="{{trans('wzoj.password')}}">
	</div>
	
	<div class="form-group">
		<label for="inputPasswordConfirm">{{trans('wzoj.password_confirmation')}}</label>
		<input type="password" class="form-control" id="inputPasswordConfirm" 
		name="password_confirmation" placeholder="{{trans('wzoj.password_confirmation')}}">
	</div>
	
	<button type="submit" class="btn btn-primary">{{trans('wzoj.submit')}}</button>

	</form>
	</div>
</div>
</div>
</div>
@endsection
