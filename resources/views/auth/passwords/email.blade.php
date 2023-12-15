@extends('layouts.master')

@section('title')
{{trans('wzoj.reset_password')}}
@endsection

@section('content')

<div class="container">
<div class="row">
<div class="col-md-12">
<form method="POST" action="/auth/password/email" class="form-padded">
	{!! csrf_field() !!}

	<div class="form-group row">
	  <label for="inputEmail" class="col-sm-2 col-form-label">{{trans('wzoj.email')}}</label>
		<div class="col-sm-10">
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
