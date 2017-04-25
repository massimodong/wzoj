@extends ('admin.layout')

@section ('title')
{{trans('wzoj.admin_home')}}
@endsection

@section ('content')
<form action="/admin/options" method="POST" class="form-horizontal">
  {{csrf_field()}}
  <h3>{{trans('wzoj.general_options')}}</h3>
  <div class="form-group">
    <label for="site_name" class="col-sm-2 control-label">{{trans('wzoj.site_name')}}:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="site_name" name="site_name" value="{{ojoption('site_name')}}">
    </div>
  </div>

  <h3 aria-describedby="helpBlockRelease">{{trans('wzoj.release_informations')}}</h3>
  <span id="helpBlockRelease" class="help-block">{{trans('wzoj.msg_release_help')}}</span>
  <div class="form-group">
    <label for="current_version_tag" class="col-sm-2 control-label">{{trans('wzoj.current_version_tag')}}:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="current_version_tag" name="current_version_tag" value="{{ojoption('current_version_tag')}}">
    </div>
  </div>
  <div class="form-group">
    <label for="current_version_id" class="col-sm-2 control-label">{{trans('wzoj.current_version_id')}}:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="current_version_id" name="current_version_id" value="{{ojoption('current_version_id')}}">
    </div>
  </div>

  <h3>{{trans('wzoj.sim_options')}}</h3>
  <div class="form-group">
    <label for="sim_threshold" class="col-sm-2 control-label">{{trans('wzoj.sim_threshold')}}:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="sim_threshold" name="sim_threshold" value="{{ojoption('sim_threshold')}}">
    </div>
  </div>

  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">{{trans('wzoj.submit')}}</button>
    </div>
  </div>
</form>
@endsection
