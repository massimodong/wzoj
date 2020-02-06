<!doctype html>
<html class="h-100" lang="en">
  <head>
    @section ('head')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v3.8.5">
    <title>@yield('title')</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="/include/css/_concated.min.css">
    <link rel="stylesheet" href="/include/css/common.css">
    <link rel="stylesheet" href="/include/css/navbar-fixed-left.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    @show
  </head>
  <body class="d-flex flex-column h-100">
  <nav class="navbar navbar-expand-sm navbar-light fixed-left" id="left-navbar">
  <a class="navbar-brand" href=@section ('home_href')"/"@show>@section('site_title'){{ojoption('site_name')}}@show</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-collapse" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbar-collapse">
    <ul class="navbar-nav">
      <li class="nav-item dropdown">
        @if (Auth::check())
          <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown" id="dropdown01" aria-haspopup="true" role="button">
            <img src="//cn.gravatar.com/avatar/{{md5(strtolower(trim(Auth::user()->email)))}}?d=retro&s=32">
          </a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdown01">
            <a class="dropdown-item" href="/users/{{Auth::user()->id}}"> {{Auth::user()->name}} </a>
            @if (Auth::user()->has_role('manager'))
              <a class="dropdown-item" href="/admin"> {{trans('wzoj.admin')}} </a>
            @endif
            <a class="dropdown-item" href="#" onclick="document.forms['logout_form'].submit();return false;"> {{trans('wzoj.logout')}} </a>
          </div>
        @else
          <li class="nav-item"><a class="nav-link" href="/auth/login"> {{trans('wzoj.login')}} </a></li>
        @endif
      </li>
    </ul>
    <ul class="navbar-nav">
      @if (empty(\Request::get('contests')))
        @section ('sidebar')
          @foreach (Cache::tags(['wzoj'])->rememberForever('sidebars', function(){return \App\Sidebar::where('index', '>', 0)->orderBy('index', 'asc')->get();}) as $sidebar)
            <li class="nav-item"><a class="nav-link" href="{{$sidebar->url}}">{{$sidebar->name}}</a></li>
          @endforeach
        @show
      @else
        @section ('sidebar')
          <li id='contests_sidebar' class="nav-item"><a class="nav-link" href="/contests"> {{trans('wzoj.contests')}} </a></li>
        @show
      @endif
    </ul>
  </div>
  </nav>

<main role="main" @yield ('main_class')>
  <div id="alerts" class="container">
    @if (isset($errors))
      @foreach ($errors->all() as $error)
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
          {{$error}}
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
      @endforeach
    @endif

    @if (session('status'))
      <div class="alert alert-success" role="alert">
        {{ session('status') }}
      </div>
    @endif
  </div>

  <div class="container h-100">
    @yield ('content')
  </div>
</main><!-- /.container -->

<form name="logout_form" action="/auth/logout" method="POST">
{{csrf_field()}}
</form>

<footer class="mt-auto">
  <div class="container text-center">
    <span class="text-muted">
    Copyright 2016
    <a class="text-muted" href="https://github.com/massimodong/wzoj" target="_blank">wzoj project</a>
    </span>
  </div>
</footer>

<script src="/include/js/_concated.min.js"></script>
<script src="/include/js/common.js"></script>
<script>
  var socket = io("{{env('SOCKET_IO_SERVER')}}");
  socket_init();
</script>
@yield ('scripts')
</body>
</html>
