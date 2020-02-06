<!doctype html>
<html lang="en">
  <head>
    @section ('head')
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Jekyll v3.8.5">
    <title>admin - @yield('title')</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="/include/css/_concated.min.css">
    <link rel="stylesheet" href="/include/css/common.css">
    <link rel="stylesheet" href="/include/css/datatables.min.css">
    <link rel="stylesheet" href="/include/css/tempusdominus-bootstrap-4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    @show
  </head>
  <body>
  <nav class="navbar navbar-expand-sm navbar-light bg-light fixed-left">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="/">
      {{ojoption('site_name')}}
    </a>
    <div class="collapse navbar-collapse" id="navbarToggler">
      <ul class="navbar-nav">
        <li class="nav-item" id='adminhome_sidebar'>
          <a class="nav-link" href="/admin"> {{trans('wzoj.admin_home')}} </a>
        </li>
        @if (Auth::user()->has_role('admin'))
        <li class="nav-item" id='appearance_sidebar'>
          <a class="nav-link" href="/admin/appearance"> {{trans('wzoj.appearance')}} </a>
        </li>
        @endif

        @if (Auth::user()->has_role('admin'))
        <li class="nav-item" id='functions_sidebar'>
          <a class="nav-link" href="/admin/functions"> {{trans('wzoj.functions')}} </a>
        </li>
        @endif

        @if (Auth::user()->has_role('group_manager'))
        <li class="nav-item" id='groups_sidebar'>
          <a class="nav-link" href="/admin/groups"> {{trans('wzoj.groups')}} </a>
        </li>
        @endif

        @if (Auth::user()->has_role('admin'))
        <li class="nav-item" id='invitations_sidebar'>
          <a class="nav-link" href="/admin/invitations"> {{trans('wzoj.invitations')}} </a>
        </li>
        @endif

        @if (Auth::user()->has_role('problem_manager'))
        <li class="nav-item" id='problems_sidebar'>
          <a class="nav-link" href="/admin/problems"> {{trans('wzoj.problems')}} </a>
        </li>
        @endif

        @if (Auth::user()->has_role('admin'))
        <li class="nav-item" id='problem_tags_sidebar'>
          <a class="nav-link" href="/admin/problem-tags"> {{trans('wzoj.tags')}} </a>
        </li>
        @endif

        @if (Auth::user()->has_role('problemset_manager'))
        <li class="nav-item" id='problemsets_sidebar'>
          <a class="nav-link" href="/s"> {{trans('wzoj.problemsets')}} </a>
        </li>
        @endif

        @if (Auth::user()->has_role('admin'))
        <li class="nav-item" id='import-problems_sidebar'>
          <a class="nav-link" href="/admin/import-problems"> {{trans('wzoj.import_problems')}} </a>
        </li>
        <li class="nav-item" id='problem-rejudge'>
          <a class="nav-link" href="/admin/problem-rejudge"> {{trans('wzoj.problem_rejudge')}} </a>
        </li>
        <li class="nav-item" id='invitations-generate'>
          <a class="nav-link" href="/admin/invitations-generate"> {{trans('wzoj.invitations_generate')}} </a>
        </li>
        <li class="nav-item" id='judgers_sidebar'>
          <a class="nav-link" href="/admin/judgers"> {{trans('wzoj.judgers')}} </a>
        </li>
        <li class="nav-item" id='roles_sidebar'>
          <a class="nav-link" href="/admin/roles"> {{trans('wzoj.roles')}} </a>
        </li>
        <li class="nav-item" id='users_sidebar'>
          <a class="nav-link" href="/admin/users"> {{trans('wzoj.users')}} </a>
        </li>
        <li class="nav-item" id='update-system'>
          <a class="nav-link" href="/admin/update-system"> {{trans('wzoj.update_system')}} </a>
        </li>
        @endif
      </ul>
    </div>
  </nav>
<main role="main" class="container">
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

  <div class="container">
    @yield ('content')
  </div>
</main><!-- /.container -->

<script src="/include/js/_concated.min.js"></script>
<script src="/include/js/common.js"></script>
<script src="/include/js/datatables.min.js"></script>
<script src="/include/js/admin.js"></script>
<script src="/include/js/moment.min.js"></script>
<script src="/include/js/tempusdominus-bootstrap-4.min.js"></script>
<script src="/include/js/tinymce/tinymce.min.js"></script>
<script src="/include/js/tinymce.js"></script>
<script>
  var socket = io("{{env('SOCKET_IO_SERVER')}}");
  socket_init();
  var csrf_token = '{{csrf_token()}}';
</script>
@yield ('scripts')
  </body>
</html>
