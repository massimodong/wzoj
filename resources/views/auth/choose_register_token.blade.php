@extends ('layouts.master')

@section ('title')
{{trans('wzoj.register')}}
@endsection

@section ('content')

<h1>{{trans('wzoj.available_tokens')}}:</h1>

<div class="row">
@if (count($invitations) > 0)
	<div class="list-group col-lg-offset-4 col-lg-4">
	@foreach ($invitations as $invitation)
		<a href='/auth/register?token={{$invitation->token}}' class="list-group-item">{{$invitation->description}}</a>
	@endforeach
	</div>
	<div class="col-lg-4"></div>
@endif
</div>

<form action='/auth/register' method='get' class="form-inline">
<div class="row">
  <label class="sr-only" for="token">{{trans('wzoj.token')}}</label>
  <input type='text' name='token' id='token' class="form-control col-lg-offset-4 col-lg-3" placeholder="{{trans('wzoj.fill_your_own_token_here')}}" required>
  <button type="submit" class="btn btn-primary col-lg-1">{{trans('wzoj.use_your_own_token')}}</button>
</div>
</form>

@endsection
