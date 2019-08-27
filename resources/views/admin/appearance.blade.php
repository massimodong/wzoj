@extends ('admin.layout')

@section ('title')
{{trans('wzoj.appearance')}}
@endsection

@section ('content')
<ul class="nav nav-tabs" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="general-tab" data-toggle="tab" href="#general" role="tab" aria-controls="genaral" aria-selected="true"> {{trans('wzoj.general_options')}} </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="sidebars-tab" data-toggle="tab" href="#sidebars" role="tab" aria-controls="sidebars" aria-selected="false"> {{trans('wzoj.sidebars')}} </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="sidepanels-tab" data-toggle="tab" href="#sidepanels" role="tab" aria-controls="sidepanels" aria-selected="false"> {{trans('wzoj.sidepanels')}} </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="diy_pages-tab" data-toggle="tab" href="#diy_pages" role="tab" aria-controls="diy_pages" aria-selected="false"> {{trans('wzoj.diy_pages')}} </a>
  </li>
</ul>

<div class="buffer-sm"></div>
<div class="tab-content">
  <div id="general" class="tab-pane fade show active" role="tabpanel" aria-labelledby="general-tab">
    <form action="/admin/options" method="POST">
      {{csrf_field()}}
      <div class="form-group row">
        <label for="logo_url" class="col-sm-2 col-form-label">{{trans('wzoj.logo_url')}}:</label>
        <div class="col-sm-8">
          <input type="text" class="form-control" id="logo_url" name="logo_url" value="{{ojoption('logo_url')}}">
        </div>
        <div class="col-sm-2">
          <img src="{{ojoption('logo_url')}}" class="navbar-logo" width="50" height="50">
        </div>
      </div>
      <div class="form-group row">
        <label for="home_diy" class="col-sm-2 col-form-label">{{trans('wzoj.home_diy')}}:</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="home_diy" name="home_diy" value="{{ojoption('home_diy')}}">
        </div>
      </div>
      <div class="form-group row">
        <label for="mathjax_url" class="col-sm-2 col-form-label">{{trans('wzoj.mathjax_url')}}:</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="mathjax_url" name="mathjax_url" value="{{ojoption('mathjax_url')}}">
        </div>
      </div>
      <div class="form-group row">
        <div class="col-sm-offset-2 col-sm-10">
          <button type="submit" class="btn btn-primary">{{trans('wzoj.submit')}}</button>
        </div>
      </div>
    </form>
  </div>
  <!-- general -->

  <div id="sidebars" class="tab-pane fade" role="tabpanel" aria-labelledby="sidebars-tab">
    <form action='/admin/appearance/sidebars' method='POST' class='form-inline'>
      {{csrf_field()}}
      <label class="sr-only" for="sidebar_name"> {{trans('wzoj.name')}}: </label>
      <input type="text" class="form-control mb-1 mr-sm-1" id="sidebar_name" name="sidebar_name" placeholder="{{trans('wzoj.name')}}">

      <label class="sr-only" for="url"> {{trans('wzoj.url')}}: </label>
      <input type="text" class="form-control mb-1 mr-sm-1" id="url" name="url" placeholder="{{trans('wzoj.url')}}">

      <label class="sr-only" for="index"> {{trans('wzoj.index')}}: </label>
      <input type="text" class="form-control mb-1 mr-sm-1" id="index" name="index" placeholder="{{trans('wzoj.index')}}">

      <button type="submit" class="btn btn-primary mb-1 mr-sm-1">+</button>
    </form>

    @foreach ($sidebars as $sidebar)
    <div class="row">
      <div class="col-11">
        <form action='/admin/appearance/sidebars/{{$sidebar->id}}' method='POST' class='form-inline'>
          {{csrf_field()}}
          {{method_field('PUT')}}

          <label for="sidebar_name"> {{trans('wzoj.name')}}: </label>
          <input type="text" class="form-control mb-1 mr-sm-1" id="sidebar_name" name="name" value="{{$sidebar->name}}">

          <label for="url"> {{trans('wzoj.url')}}: </label>
          <input type="text" class="form-control mb-1 mr-sm-1" id="url" name="url" value="{{$sidebar->url}}">

          <label for="index"> {{trans('wzoj.index')}}: </label>
          <input type="text" class="form-control mb-1 mr-sm-1" id="index" name="index" value="{{$sidebar->index}}">

          <button type="submit" class="btn btn-primary mb-1 mr-sm-1">{{trans('wzoj.edit')}}</button>
        </form>
      </div>
      <div class="col-1">
        <form action='/admin/appearance/sidebars/{{$sidebar->id}}' method='POST'>
          {{csrf_field()}}
          {{method_field('DELETE')}}
          <button type="submit" class="btn btn-danger">{{trans('wzoj.delete')}}</button>
        </form>
      </div>
    </div>
    @endforeach
  </div>
  <!-- sidebars -->

  <div id="sidepanels" class="tab-pane fade" role="tabpanel" aria-labelledby="sidepanels-tab">
    <form action='/admin/appearance/side-panels' method='POST'>
      {{csrf_field()}}
      <button type="submit" class="btn btn-primary">+</button>
    </form>
    <table class="table">
      <thead>
        <tr>
          <th>{{trans('wzoj.index')}}</th>
          <th>{{trans('wzoj.title')}}</th>
        </tr>
      </thead>
      <tbody>
      @foreach ($sidePanels as $sidePanel)
        <tr>
          <th>{{$sidePanel->index}}</th>
          <th><a href="/admin/appearance/side-panels/{{$sidePanel->id}}">{{$sidePanel->title}}</a></th>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
  <!-- sidepanels -->

  <div id="diy_pages" class="tab-pane fade" role="tabpanel" aria-labelledby="diy_pages-tab">
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
