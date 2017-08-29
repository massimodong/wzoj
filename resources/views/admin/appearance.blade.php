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
        <label for="home_diy" class="col-xs-2 control-label">{{trans('wzoj.home_diy')}}:</label>
        <div class="col-xs-10">
          <input type="text" class="form-control" id="home_diy" name="home_diy" value="{{ojoption('home_diy')}}">
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
    <form action='/admin/appearance/sidebars' method='POST' class='form-inline col-xs-12'>
      {{csrf_field()}}
      <div class="form-group">
        <label for="sidebar_name"> {{trans('wzoj.name')}}: </label>
	<input type="text" class="form-control" id="sidebar_name" name="sidebar_name">
      </div>
      <div class="form-group">
        <label for="url"> {{trans('wzoj.url')}}: </label>
	<input type="text" class="form-control" id="url" name="url">
      </div>
      <div class="form-group">
        <label for="index"> {{trans('wzoj.index')}}: </label>
	<input type="text" class="form-control" id="index" name="index">
      </div>
      <button type="submit" class="btn btn-default">+</button>
    </form>
    <div class="col-xs-12" style="height:15px"></div>
    @foreach ($sidebars as $sidebar)
      <form action='/admin/appearance/sidebars/{{$sidebar->id}}' method='POST' class='form-inline col-xs-11'>
        {{csrf_field()}}
        {{method_field('PUT')}}
        {{$sidebar->id}}:
        <div class="form-group">
          <label for="sidebar_name"> {{trans('wzoj.name')}}: </label>
	  <input type="text" class="form-control" id="sidebar_name" name="name" value="{{$sidebar->name}}">
        </div>
        <div class="form-group">
          <label for="url"> {{trans('wzoj.url')}}: </label>
          <input type="text" class="form-control" id="url" name="url" value="{{$sidebar->url}}">
        </div>
	<div class="form-group">
          <label for="index"> {{trans('wzoj.index')}}: </label>
	  <input type="text" class="form-control" id="index" name="index" value="{{$sidebar->index}}">
        </div>
        <button type="submit" class="btn btn-primary">{{trans('wzoj.edit')}}</button>
      </form>
      <form action='/admin/appearance/sidebars/{{$sidebar->id}}' method='POST' class='col-xs-1'>
        {{csrf_field()}}
        {{method_field('DELETE')}}
        <button type="submit" class="btn btn-danger">{{trans('wzoj.delete')}}</button>
      </form>
    @endforeach
  </div>
  <!-- sidebars -->

  <div id="diy_pages" class="tab-pane">
    <form action='/admin/appearance/diy-pages' method='POST'>
      {{csrf_field()}}
      <button type="submit" class="btn btn-default">+</button>
    </form>
    <table class="table table-striped">
      <thead>
        <tr>
	  <th>{{trans('wzoj.id')}}</th>
	  <th>{{trans('wzoj.name')}}</th>
	  <th>{{trans('wzoj.url')}}</th>
	</tr>
      </thead>
      <tbody>
      @foreach ($diyPages as $diyPage)
	<tr>
	  <th>{{$diyPage->id}}</th>
	  <th><a href="/admin/appearance/diy-pages/{{$diyPage->id}}">{{$diyPage->name}}</a></th>
	  <th><a href="/{{$diyPage->url}}">{{$diyPage->url}}</a></th>
	</tr>
      @endforeach
      </tbody>
    </table>
  </div>
  <!-- diy_pages -->

</div>
@endsection

@section ('scripts')
<script>
selectHashTab();
</script>
@endsection
