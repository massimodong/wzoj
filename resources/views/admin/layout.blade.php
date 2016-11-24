<html>

<head>
<title>admin - @yield('title')</title>
</head>

<body>
@section ('sidebar')
<p><a href='/'>home</a></p>
@if (Auth::check()) 
<p><a href='/auth/logout'>logout</a></p>
@else
<p><a href='/auth/login'>login</a></p>
@endif
<p><a href='/auth/register'>Register</a></p>

<hr />
<p><a href='/admin/groups'>groups</a></p>
<p><a href='/admin/invitations'>invitations</a></p>
@show

@if (Auth::check())
	current user:<a href='/users/{{Auth::user()->id}}'>{{Auth::user()->fullname}}</a>
@endif

<hr />

@if (count($errors) > 0)
	<div class="alert alert-danger">
	<strong>Whoops! Something went wrong!</strong>

	<br><br>

	<ul>
	@foreach ($errors->all() as $error)
	<li>{{ $error }}</li>
	@endforeach
	</ul>
	</div>
	<hr />
@endif

<h3>Admin Page</h3>

<div>
@yield ('content')
</div>

</body>
</html>
