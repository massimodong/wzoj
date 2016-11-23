@extends ('layouts.master')

@section ('title')
Register
@endsection

@section ('content')

<h4>Available tokens:</h4>

@if (count($invitations) > 0)
	@foreach ($invitations as $invitation)
		<p><a href='/auth/register?token={{$invitation->token}}'>{{$invitation->description}}</a></p>
	@endforeach
@else
	<p>No available tokens!</p>
@endif

<form action='/auth/register' method='get'>
My own token:<input type='text' name='token'>
<button type='submit'>submit</button>
</form>

@endsection
