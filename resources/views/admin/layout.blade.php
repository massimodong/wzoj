<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1024">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>admin - @yield('title')</title>

    <link href="{{ojcache('/include/css/_bower.min.css')}}" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ojcache('/include/css/sb-admin.css')}}" rel="stylesheet">
    <link href="{{ojcache('/include/css/common.css')}}" rel="stylesheet">

    <script>
    var csrf_token = '{{csrf_token()}}'
    </script>

    <script src="{{ojcache('/include/js/lang/zh.js')}}"></script>
    <script src="{{ojcache('/include/js/common.js')}}"></script>
</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/">{{ojoption('site_name')}}</a>
            </div>
            <!-- Top Menu Items -->
	    <ul class="nav navbar-nav">
		@section ('sidebar')
	    	<li><a>|</a></li>
		@show
	    </ul>
            <ul class="nav navbar-right top-nav">
                <li>
                    <a> {{Auth::user()->name}} </a>
		</li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
		    <li id='home_sidebar'>
		    	<a href="/admin"> {{trans('wzoj.admin_home')}} </a>
		    </li>

		    @if (Auth::user()->has_role('admin'))
		    <li id='notice_sidebar'>
                        <a href="/admin/notice"> {{trans('wzoj.notices')}} </a>
		    </li>
		    @endif

		    @if (Auth::user()->has_role('group_manager'))
                    <li id='groups_sidebar'>
                        <a href="/admin/groups"> {{trans('wzoj.groups')}} </a>
                    </li>
		    @endif

		    @if (Auth::user()->has_role('admin'))
                    <li id='invitations_sidebar'>
                        <a href="/admin/invitations"> {{trans('wzoj.invitations')}} </a>
                    </li>
		    @endif

		    @if (Auth::user()->has_role('problem_manager'))
                    <li id='problems_sidebar'>
                        <a href="/admin/problems"> {{trans('wzoj.problems')}} </a>
                    </li>
		    @endif

		    @if (Auth::user()->has_role('admin'))
		    <li id='problem_tags_sidebar'>
		    	<a href="/admin/problem-tags"> {{trans('wzoj.tags')}} </a>
		    </li>
		    @endif

		    @if (Auth::user()->has_role('problemset_manager'))
		    <li id='problemsets_sidebar'>
		    	<a href="/s"> {{trans('wzoj.problemsets')}} </a>
		    </li>
		    @endif

		    @if (Auth::user()->has_role('admin'))
                    <li id='import-problems_sidebar'>
                        <a href="/admin/import-problems"> {{trans('wzoj.import_problems')}} </a>
                    </li>
		    <li id='problem-rejudge'>
		    	<a href="/admin/problem-rejudge"> {{trans('wzoj.problem_rejudge')}} </a>
		    </li>
		    <li id='invitations-generate'>
		    	<a href="/admin/invitations-generate"> {{trans('wzoj.invitations_generate')}} </a>
		    </li>
		    <li id='judgers_sidebar'>
		    	<a href="/admin/judgers"> {{trans('wzoj.judgers')}} </a>
		    </li>
		    <li id='roles_sidebar'>
		    	<a href="/admin/roles"> {{trans('wzoj.roles')}} </a>
		    </li>
		    <li id='database_backup_sidebar'>
		    	<a href="/admin/database-backup"> {{trans('wzoj.database_backup')}} </a>
		    </li>
		    <li id='update-system'>
		    	<a href="/admin/update-system"> {{trans('wzoj.update_system')}} </a>
		    </li>
		    <li id='advanced-settings'>
		    	<a href="/admin/advanced-settings"> {{trans('wzoj.advanced_settings')}} </a>
		    </li>
		    @endif
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </nav>

        <div id="page-wrapper" style="min-height: 600px;">

	    <div id="alerts">
	        @foreach ($errors->all() as $error)
	    	    <div class="alert alert-warning alert-dismissable">
		    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
		     {{$error}}
		    </div>
		@endforeach
	    </div>
	    <!-- alerts -->

            <div class="container-fluid">

                <!-- Page Heading -->
                <div class="row-fluid">
                    <div class="col-lg-12">
                        <h1 class="page-header">
                            @yield ('title')
                        </h1>
			@yield ('content')
                    </div>
                </div>
                <!-- /.row-fluid -->

            </div>
            <!-- /.container-fluid -->

        </div>
        <!-- /#page-wrapper -->
	
    </div>
    <!-- /#wrapper -->

    <script src="{{ojcache('/include/js/_bower.min.js')}}"></script>

    <script src="{{ojcache('/vendor/tinymce/tinymce.min.js')}}"></script>
    <script src="{{ojcache('/include/js/tinymce.js')}}"></script>

    @yield ('scripts')
</body>

</html>
