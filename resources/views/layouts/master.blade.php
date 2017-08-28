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
    <meta name="google" content="notranslate" />
    <link rel="icon" href="/favicon.ico">

    <title>@yield('title')</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ojcache('/include/css/_bower.min.css')}}" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{ojcache('/include/css/common.css')}}" rel="stylesheet">

    <!-- bootstrap-datetimepicker -->
    <link href="{{ojcache('/include/css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet">
    
    <script src="{{ojcache('/include/js/lang/zh.js')}}"></script>
    <script src="{{ojcache('/include/js/common.js')}}"></script>
    <script src="{{ojcache('/include/js/solution.js')}}"></script>
    <script src="{{ojcache('/include/js/ranklist.js')}}"></script>

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
          <a href="/"><img src="{{ojoption('logo_url')}}" class="navbar-logo" width="50" height="50"></a>
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
	  @if (empty(\Request::get('contests')))
	  @section ('sidebar')
            <li id='home_sidebar'><a href="/"> {{trans('wzoj.home')}} </a></li>
            <li id='problemsets_sidebar'><a href="/s"> {{trans('wzoj.problemsets')}} </a></li>
            <li id='contests_sidebar'><a href="/contests"> {{trans('wzoj.contests')}} </a></li>
            <li id='solutions_sidebar'><a href="/solutions"> {{trans('wzoj.solutions')}} </a></li>
            @if (ojoption('forum_enabled') || (Auth::check() && Auth::user()->has_role('admin')))
            <li id='forum_sidebar'><a href="/forum"> {{trans('wzoj.forum')}} </a></li>
            @endif
          @show
	  @else
	  @section ('sidebar')
            <li id='contests_sidebar'><a href="/contests"> {{trans('wzoj.contests')}} </a></li>
          @show
	  @endif

          @foreach (Cache::tags(['wzoj'])->rememberForever('sidebars', function(){return \App\Sidebar::all();}) as $sidebar)
	    <li><a href="{{$sidebar->url}}">{{$sidebar->name}}</a></li>
          @endforeach
          </ul>
          <ul class="nav navbar-nav navbar-right">
	    @if (Auth::check())
	      <li class="dropdown">
	        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{Auth::user()->name}} <span class="caret"></span></a>
		<ul class="dropdown-menu">
		  <li><a href="/users/{{Auth::user()->id}}"> {{trans('wzoj.profile')}} </a></li>
		  @if (Auth::user()->has_role('manager'))
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
    @if (isset($errors))
    @foreach ($errors->all() as $error)
	<div class="alert alert-warning alert-dismissable">
	    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
	    {{$error}}
        </div>
    @endforeach
    @endif
    @if (session('status'))
    	<div class="alert alert-success">
        	{{ session('status') }}
    	</div>
    @endif
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
	    <p class="text-muted">
	    	Copyright 2016
		<a href="https://github.com/massimodong/wzoj" target="_blank">wzoj project</a>
                {{ojoption('current_version_tag')}}
                @if (strlen(ojoption('icp')))
                  | <a href="http://www.miitbeian.gov.cn/" target="_blank" style="color:grey">{{ojoption('icp')}}</a>
                @endif
	    </p>
	</div>
    </footer>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="{{ojcache('/include/js/_bower.min.js')}}"></script>
    <!-- tinymce -->
    <script src="{{ojcache('/vendor/tinymce/tinymce.min.js')}}"></script>
    <script src="{{ojcache('/include/js/tinymce.js')}}"></script>
    <!-- bootstrap-datetimepicker -->
    <script src="{{ojcache('/include/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="{{ojcache('/include/js/bootstrap-datetimepicker.zh-CN.js')}}"></script>
    @yield ('scripts')
  </body>
</html>
