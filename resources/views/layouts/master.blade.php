<!DOCTYPE html>
<html lang="en">
  <head>
    @section ('head')
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
    <meta name="viewport" content="width=1024">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="/favicon.ico">

    <title>@yield('title')</title>

    <!-- Bootstrap core CSS -->
    <link href="/vendor/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="/vendor/bootstrap/docs/assets/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <link href="/vendor/bootstrap-fileinput/css/fileinput.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/include/css/common.css" rel="stylesheet">
    <link href="/include/css/syntaxhighlighter.css" rel="stylesheet">

    <!--Data Tables-->
    <link href="/include/css/datatables.min.css" rel="stylesheet">
    <!-- bootstrap-select -->
    <link href="/include/css/bootstrap-select.min.css" rel="stylesheet">
    <!-- bootstrap-datetimepicker -->
    <link href="/include/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="/vendor/bootstrap/docs/assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src='/include/js/lang/zh.js'></script>
    <script src='/include/js/common.js'></script>
    <script src='/include/js/solution.js'></script>
    <script src='/include/js/ranklist.js'></script>
    <script src='/include/js/syntaxhighlighter.js'></script>

    <script>
    var csrf_token = '{{csrf_token()}}';
    </script>

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
          <a class="navbar-brand" href=
	  @section ('home_href')
	  "/"
	  @show
	  >
	  @section ('site_title')
		{{ojoption('site_name')}}
	  @show
	  </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
	  @section ('sidebar')
            <li id='home_sidebar'><a href="/"> {{trans('wzoj.home')}} </a></li>
            <li id='problemsets_sidebar'><a href="/s"> {{trans('wzoj.problemsets')}} </a></li>
            <li id='solutions_sidebar'><a href="/solutions"> {{trans('wzoj.solutions')}} </a></li>
          @show
          </ul>
          <ul class="nav navbar-nav navbar-right">
	    @if (Auth::check())
	      <li class="dropdown">
	        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{Auth::user()->fullname}} <span class="caret"></span></a>
		<ul class="dropdown-menu">
		  <li><a href="/users/{{Auth::user()->id}}"> {{trans('wzoj.profile')}} </a></li>
		  @if (Auth::user()->has_role('admin'))
		  <li><a href="/admin"> {{trans('wzoj.admin')}} </a></li>
		  @endif
		  <li><a href="#" onclick="document.forms['logout_form'].submit();return false;"> {{trans('wzoj.logout')}} </a></li>
		</ul>
	      </li>
	    @else
	      <li class="dropdown">
	        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"> {{trans('wzoj.account')}} <span class="caret"></span></a>
		<ul class="dropdown-menu">
		  <li><a href="/auth/login"> {{trans('wzoj.login')}} </a></li>
		  <li><a href="/auth/register"> {{trans('wzoj.register')}} </a></li>
		</ul>
	      </li>
	    @endif
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div id="alerts" class="container">
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

    <footer class="footer">
        <div class="container">
	    <p class="text-muted">Copyright 2016 <a href="https://github.com/massimodong/wzoj" target="_blank">wzoj project</a></p>
	</div>
    </footer>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/vendor/bootstrap/docs/assets/js/vendor/jquery.min.js"></script>
    <!-- <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script> -->
    <script src="/vendor/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- bootstrap-fileinput -->
    <script src="/vendor/bootstrap-fileinput/js/fileinput.min.js"></script>
    <script src="/vendor/bootstrap-fileinput/themes/fa/theme.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="/vendor/bootstrap/docs/assets/js/ie10-viewport-bug-workaround.js"></script>
    <!-- tinymce -->
    <script src='/vendor/tinymce/tinymce.min.js'></script>
    <script src='/include/js/tinymce.js'></script>
    <script src='/include/js/isotope.pkgd.min.js'></script>
    <!-- Data Tables-->
    <script src='/include/js/datatables.min.js'></script>
    <!-- bootstrap-select -->
    <script src='/include/js/bootstrap-select.min.js'></script>
    <script src='/include/js/bootstrap-select-zh_CN.js'></script>
    <!-- Chart.js-->
    <script src='/include/js/Chart.js'></script>
    <!-- bootstrap-datetimepicker -->
    <script src='/include/js/bootstrap-datetimepicker.min.js'></script>
    <script src='/include/js/bootstrap-datetimepicker.zh-CN.js'></script>
    @yield ('scripts')
  </body>
</html>
