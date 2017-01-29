<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>admin - @yield('title')</title>

    <!-- Bootstrap Core CSS -->
    <link href="/vendor/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="/include/css/sb-admin.css" rel="stylesheet">
    <link href="/include/css/common.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <!--<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"> -->

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
    var csrf_token = '{{csrf_token()}}'
    </script>

    <script src='/include/js/common.js'></script>
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
                    <a> {{Auth::user()->fullname}} </a>
		</li>
            </ul>
            <!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
            <div class="collapse navbar-collapse navbar-ex1-collapse">
                <ul class="nav navbar-nav side-nav">
		    <li id='home_sidebar'>
		    	<a href="/admin"> Admin-Home</a>
		    </li>
                    <li id='groups_sidebar'>
                        <a href="/admin/groups"> Groups</a>
                    </li>
                    <li id='invitations_sidebar'>
                        <a href="/admin/invitations"> Invitations</a>
                    </li>
                    <li id='problems_sidebar'>
                        <a href="/admin/problems"> Problems</a>
                    </li>
                    <li id='import-problems_sidebar'>
                        <a href="/admin/import-problems"> Import-Problems</a>
                    </li>
		    <li id='problem-rejudge'>
		    	<a href="/admin/problem-rejudge"> Problem-rejudge</a>
		    </li>
		    <li id='update-system'>
		    	<a href="/admin/update-system"> Update-system</a>
		    </li>
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

    <!-- jQuery -->
    <script src="/vendor/bootstrap/docs/assets/js/vendor/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="/vendor/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- tinymce -->
    <script src='/vendor/tinymce/tinymce.min.js'></script>
    <script src='/include/js/tinymce.js'></script>
</body>

</html>
