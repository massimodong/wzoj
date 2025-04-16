@extends ('admin.layout')

@section ('title')
{{trans('wzoj.admin_home')}}
@endsection

@section ('content')
<form action="/admin/options" method="POST">
  {{csrf_field()}}
  <h3>{{trans('wzoj.general_options')}}</h3>
  <div class="form-group row">
    <label for="site_name" class="col-sm-2 control-label">{{trans('wzoj.site_name')}}:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="site_name" name="site_name" value="{{ojoption('site_name')}}">
    </div>
  </div>
  <div class="form-group row">
    <label for="site_description" class="col-sm-2 control-label">{{trans('wzoj.site_description')}}:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="site_description" name="site_description" value="{{ojoption('site_description')}}">
    </div>
  </div>
  <div class="form-group row">
    <label for="allowed_languages" class="col-sm-2 control-label">{{trans('wzoj.allowed_languages')}}:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="allowed_languages" name="allowed_languages" value="{{ojoption('allowed_languages')}}">
    </div>
  </div>
  <div class="form-group row">
    <label for="ranklist_mode" class="col-sm-2 control-label">{{trans('wzoj.ranklist_mode')}}:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="ranklist_mode" name="ranklist_mode" value="{{ojoption('ranklist_mode')}}">
    </div>
  </div>
  <div class="form-group row">
    <label for="logo_url" class="col-sm-2 control-label">{{trans('wzoj.logo_url')}}:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="logo_url" name="logo_url" value="{{ojoption('logo_url')}}">
    </div>
  </div>
  <div class="form-group row">
    <label for="icp" class="col-sm-2 control-label">{{trans('wzoj.icp')}}:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="icp" name="icp" value="{{ojoption('icp')}}">
    </div>
  </div>
  <div class="form-group row">
    <label for="gongan_icon_url" class="col-sm-2 control-label">{{trans('wzoj.gongan_icon_url')}}:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="gongan_icon_url" name="gongan_icon_url" value="{{ojoption('gongan_icon_url')}}">
    </div>
  </div>
  <div class="form-group row">
    <label for="gongan_code" class="col-sm-2 control-label">{{trans('wzoj.gongan_code')}}:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="gongan_code" name="gongan_code" value="{{ojoption('gongan_code')}}">
    </div>
  </div>
  <div class="form-group row">
    <label for="gongan_name" class="col-sm-2 control-label">{{trans('wzoj.gongan_name')}}:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="gongan_name" name="gongan_name" value="{{ojoption('gongan_name')}}">
    </div>
  </div>

  <h3 aria-describedby="helpBlockRelease">{{trans('wzoj.release_informations')}}</h3>
  <span id="helpBlockRelease" class="help-block">{{trans('wzoj.msg_release_help')}}</span>
  <div class="form-group row">
    <label for="current_version_tag" class="col-sm-2 control-label">{{trans('wzoj.current_version_tag')}}:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="current_version_tag" name="current_version_tag" value="{{ojoption('current_version_tag')}}">
    </div>
  </div>
  <div class="form-group row">
    <label for="current_version_id" class="col-sm-2 control-label">{{trans('wzoj.current_version_id')}}:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="current_version_id" name="current_version_id" value="{{ojoption('current_version_id')}}">
    </div>
  </div>

  <h3>{{trans('wzoj.sim_options')}}</h3>
  <div class="form-group row">
    <label for="sim_threshold" class="col-sm-2 control-label">{{trans('wzoj.sim_threshold')}}:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="sim_threshold" name="sim_threshold" value="{{ojoption('sim_threshold')}}">
    </div>
  </div>

  <h3>{{trans('wzoj.contest_options')}}</h3>
  <div class="form-group row">
    <label for="contest_problemsets" class="col-sm-2 control-label">{{trans('wzoj.contest_problemsets')}}:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="contest_problemsets" name="contest_problemsets" value="{{ojoption('contest_problemsets')}}">
    </div>
  </div>

  <h3>{{trans('wzoj.forum_options')}}</h3>
  <div class="form-group row">
    <label for="forum_enabled" class="col-sm-2 control-label">{{trans('wzoj.forum_enabled')}}:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="forum_enabled" name="forum_enabled" value="{{ojoption('forum_enabled')}}">
    </div>
  </div>

  <h3>{{trans('wzoj.verification_code_options')}}</h3>
  <div class="form-group row">
    <label for="forum_enabled" class="col-sm-2 control-label">{{trans('wzoj.alibaba_sms_templates')}}:</label>
    <div class="col-sm-10">
      <textarea type="text" class="form-control" id="sms_templates" name="sms_templates">{{ojoption('sms_templates')}}</textarea>
    </div>
  </div>

  <div class="form-group row">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary">{{trans('wzoj.submit')}}</button>
    </div>
  </div>
</form>
@endsection
