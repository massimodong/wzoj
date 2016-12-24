<!DOCTYPE html>
<html lang="en">
  <head>
    @section ('head')
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/favicon.ico">

    <title>@yield('title')</title>

    <!-- Bootstrap core CSS -->
    <link href="/vendor/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="/vendor/bootstrap/docs/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/include/css/common.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="/vendor/bootstrap/docs/assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src='/include/js/common.js'></script>
    @show
  </head>

  <body>

    <!-- Static navbar -->
    <nav class="navbar navbar-default navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">{{ojoption('site_name')}}</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
	  @section ('sidebar')
            <li id='home_sidebar'><a href="/">Home</a></li>
            <li id='problemsets_sidebar'><a href="/s">problemsets</a></li>
            <li id='solutions_sidebar'><a href="/solutions">solutions</a></li>
          @show
          </ul>
          <ul class="nav navbar-nav navbar-right">
	    @if (Auth::check())
	      <li class="dropdown">
	        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{Auth::user()->fullname}} <span class="caret"></span></a>
		<ul class="dropdown-menu">
		  <li><a href="/users/{{Auth::user()->id}}">profile</a></li>
		  @if (Auth::user()->has_role('admin'))
		  <li><a href="/admin">admin</a></li>
		  @endif
		  <li><a href="#" onclick="document.forms['logout_form'].submit();return false;">logout</a></li>
		</ul>
	      </li>
	    @else
	      <li class="dropdown">
	        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Account <span class="caret"></span></a>
		<ul class="dropdown-menu">
		  <li><a href="/auth/login">login</a></li>
		  <li><a href="/auth/register">register</a></li>
		</ul>
	      </li>
	    @endif
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">
    @foreach ($errors->all() as $error)
	<div class="alert alert-warning alert-dismissable">
	    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
	    {{$error}}
        </div>
    @endforeach
    </div>


    <div class="container">
    	@yield ('content')
    </div> <!-- /container -->

    <!-- logout form -->
    <form name="logout_form" action="/auth/logout" method="POST">
	{{csrf_field()}}
    </form>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/vendor/bootstrap/docs/assets/js/vendor/jquery.min.js"></script>
    <!-- <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script> -->
    <script src="/vendor/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="/vendor/bootstrap/docs/assets/js/ie10-viewport-bug-workaround.js"></script>
    @yield ('scripts')
  </body>
</html>
