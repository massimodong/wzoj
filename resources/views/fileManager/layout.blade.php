<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=1024">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{trans('wzoj.file_manager')}}:@yield('title')</title>

    <link href="{{ojcache('/include/css/_concated.min.css')}}" rel="stylesheet">
    <link href="{{ojcache('/include/css/fileinput.min.css')}}" rel="stylesheet">
    <link href="{{ojcache('/include/css/common.css')}}" rel="stylesheet">

    <script>
    var csrf_token = '{{csrf_token()}}'
    </script>

    <script src="{{ojcache('/include/js/common.js')}}"></script>
</head>

<body>
  <div id="page-wrapper">
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

  <script src="{{ojcache('/include/js/admin.js')}}"></script>
  <script src="{{ojcache('/include/js/_concated.min.js')}}"></script>
  <script src="{{ojcache('/include/js/fileinput.min.js')}}"></script>
  <script src="/include/js/datatables.min.js"></script>

  @yield ('scripts')
</body>

</html>
