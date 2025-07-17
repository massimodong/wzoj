@extends ('admin.layout')

@section ('title')
{{trans('wzoj.users')}}
@endsection

@section ('content')

<h2>{{trans('wzoj.search_user')}}</h2>
@if (isset($search_user))
  <table class="table">
    <tbody>
      <tr>
        <th scope="row">ID</th>
        <td>{{$search_user->id}}</td>
      </tr>
      <tr>
        <th scope="row">{{trans('wzoj.username')}}</th>
        <td>{{$search_user->name}}</td>
      </tr>
      <tr>
        <th scope="row">{{trans('wzoj.email')}}</th>
        <td>{{$search_user->email}}</td>
      </tr>
      <tr>
        <th scope="row">{{trans('wzoj.phone')}}</th>
        <td>{{$search_user->phone_number}}</td>
      </tr>
      <tr>
        <th scope="row">BT</th>
        <td>{{$search_user->bot_tendency}}</td>
      </tr>
    </tbody>
  </table>
@endif

<form method='GET' class="form-inline">
  <label for="uid" class="sr-only"></label>
  <select name="uid" id="uid" class="selectpicker mb-2 mr-2" data-live-search="true" title="{{trans('wzoj.search_user')}}">
  @foreach (\App\User::orderBy('id', 'asc')->get(['id', 'name']) as $user)
    <option value="{{$user->id}}">{{$user->id}}-{{$user->name}}</option>
  @endforeach
  </select>

  <button type="submit" class="btn btn-primary mb-2">{{trans('wzoj.search')}}</button>
</form>

<hr>

<form action="/admin/users" method="POST">
  {{csrf_field()}}
  <div class="form-group row">
    <label for="user_id" class="col-sm-2 col-form-label"> ID </label>
    <div class="col-sm-2">
      <input type="text" class="form-control" id="user_id" name="id" required>
    </div>
    <p><span style="color:red"> *{{trans('wzoj.msg_users_update_id_name_match')}} </span></p>
  </div>
  <div class="form-group row">
    <label for="name" class="col-sm-2 col-form-label"> {{trans('wzoj.username')}} </label>
    <div class="col-sm-2">
      <input type="text" class="form-control" id="name" name="name" required>
    </div>
  </div>
  <div class="form-group row">
    <label for="phone" class="col-sm-2 col-form-label"> {{trans('wzoj.phone')}} </label>
    <div class="col-sm-2">
      <input type="text" class="form-control" id="phone" name="phone">
    </div>
    <p><span style="color:red"> *{{trans('wzoj.msg_blank_no_effect')}} </span></p>
  </div>
  <div class="form-group row">
    <label for="new_password" class="col-sm-2 col-form-label"> {{trans('wzoj.new_password')}} </label>
    <div class="col-sm-2">
      <input type="text" class="form-control" id="new_password" name="new_password">
    </div>
    <p><span style="color:red"> *{{trans('wzoj.msg_blank_no_effect')}} </span></p>
  </div>
  <div class="form-group row">
    <label for="bt" class="col-sm-2 col-form-label"> BT </label>
    <div class="col-sm-2">
      <input type="text" class="form-control" id="bt" name="bt">
    </div>
    <p><span style="color:red"> *{{trans('wzoj.msg_users_update_bot_tendency_explain')}} </span></p>
  </div>

  <button type="submit" class="btn btn-primary">{{trans('wzoj.update')}}</button>
</form>
@endsection
