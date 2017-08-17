@extends ('admin.layout')

@section ('title')
{{trans('wzoj.appearance')}}
@endsection

@section ('content')
<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#general"> {{trans('wzoj.general_options')}} </a></li>
  <li><a data-toggle="tab" href="#sidebars"> {{trans('wzoj.sidebars')}} </a></li>
  <li><a data-toggle="tab" href="#diy_pages"> {{trans('wzoj.diy_pages')}} </a></li>
</ul>

<div class="top-buffer-sm"></div>
<div class="tab-content">
  <div id="general" class="tab-pane in active">
    <form action="/admin/options" method="POST" class="form-horizontal">
      {{csrf_field()}}
      <div class="form-group">
        <label for="logo_url" class="col-xs-2 control-label">{{trans('wzoj.logo_url')}}:</label>
        <div class="col-xs-8">
          <input type="text" class="form-control" id="logo_url" name="logo_url" value="{{ojoption('logo_url')}}">
        </div>
	<div class="col-xs-2">
	  <img src="{{ojoption('logo_url')}}" class="navbar-logo" width="50" height="50">
	</div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-default">{{trans('wzoj.submit')}}</button>
        </div>
      </div>
    </form>
  </div>
  <!-- general -->
  <div id="sidebars" class="tab-pane">
  2
  </div>
  <!-- sidebars -->

  <div id="diy_pages" class="tab-pane">
  3
  </div>
  <!-- diy_pages -->

</div>
@endsection

@section ('scripts')
<script>
selectHashTab();
</script>
@endsection
