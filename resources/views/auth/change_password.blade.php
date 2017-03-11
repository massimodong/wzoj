@extends ('layouts.master')

@section ('title')
{{ojoption('site_name')}}
@endsection

@section ('content')
<div class="col-xs-offset-3 col-xs-6">

<form method="POST" class="form-horizontal">
  {{csrf_field()}}
  <div class="form-group">
    <label for="name" class="col-sm-2 control-label">{{trans('wzoj.username')}}</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="name" name="name" value="{{Auth::user()->name}}" required>
    </div>
  </div>
  <div class="form-group">
    <label for="email" class="col-sm-2 control-label">{{trans('wzoj.email')}}</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="email" name="email" value="{{Auth::user()->email}}" required>
    </div>
  </div>
  <div class="form-group">
    <label for="new_password" class="col-sm-2 control-label">{{trans('wzoj.new_password')}}</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="new_password" name="new_password">
    </div>
  </div>
  <div class="form-group">
    <label for="new_password_confirmation" class="col-sm-2 control-label">{{trans('wzoj.password_confirmation')}}</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation">
    </div>
  </div>
  <hr>
  <div class="form-group">
    <label for="old_password" class="col-sm-2 control-label">{{trans('wzoj.old_password')}}</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="old_password" name="old_password">
    </div>
  </div>

  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">{{trans('wzoj.submit')}}</button>
    </div>
  </div>
</form>

</div>
@endsection
