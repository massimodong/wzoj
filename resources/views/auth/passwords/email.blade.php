@extends('layouts.master')

@section('title')
{{trans('wzoj.reset_password')}}
@endsection

@section('content')

<div class="container">
<div class="row">
<div class="col-md-12">
<form method="POST" action="/auth/password/email">
	{!! csrf_field() !!}
	<div class="col-md-offset-4 col-md-4">

	<div class="form-group row">
		<div class="col-md-12">
			<label for="inputEmail">{{trans('wzoj.email')}}</label>
			<input type="email" class="form-control" id="inputEmail"
			name="email" placehold="{{trans('wzoj.email')}}" value="" required autofocus>
		</div>
	</div>
	
  @include ('partials.captcha.challenge')
	
	<div class=form-group>
		<button type="submit" class="form-control">{{trans('wzoj.send_password_reset_link')}}</button>
	</div>
	</form>

	</div>

</div>
</div>
</div>
@endsection
