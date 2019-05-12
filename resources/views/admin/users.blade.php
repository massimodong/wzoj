@extends ('admin.layout')

@section ('title')
{{trans('wzoj.users')}}
@endsection

@section ('content')
<form action="/admin/users" method="POST" class="form-horizontal">
  {{csrf_field()}}
  <div class="form-group">
    <label for="user_id" class="col-sm-2 control-label"> ID </label>
    <div class="col-sm-2">
      <input type="text" class="form-control" id="user_id" name="id" value="{{old('id')}}" required>
    </div>
    <p><span style="color:red"> *{{trans('wzoj.msg_users_update_id_name_match')}} </span></p>
  </div>
  <div class="form-group">
    <label for="name" class="col-sm-2 control-label"> {{trans('wzoj.username')}} </label>
    <div class="col-sm-2">
      <input type="text" class="form-control" id="name" name="name" value="{{old('name')}}" required>
    </div>
  </div>
  <div class="form-group">
    <label for="new_password" class="col-sm-2 control-label"> {{trans('wzoj.new_password')}} </label>
    <div class="col-sm-2">
      <input type="text" class="form-control" id="new_password" name="new_password" value="">
    </div>
    <p><span style="color:red"> *{{trans('wzoj.msg_blank_no_effect')}} </span></p>
  </div>
  <div class="form-group">
    <label for="bt" class="col-sm-2 control-label"> BT </label>
    <div class="col-sm-2">
      <input type="text" class="form-control" id="bt" name="bt" value="{{old('bt')}}">
    </div>
    <p><span style="color:red"> *{{trans('wzoj.msg_users_update_bot_tendency_explain')}} </span></p>
  </div>

  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-default">{{trans('wzoj.update')}}</button>
    </div>
  </div>
</form>
@endsection
