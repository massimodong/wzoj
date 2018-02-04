@extends ('admin.layout')

@section ('title')
{{trans('wzoj.roles')}}
@endsection

@section ('content')
<div class="col-xs-12">
  <p><span style="color:red">{{trans('wzoj.msg_roles_admin_helper')}}</span></p>
</div>

<div class="col-xs-10">
  <form method="POST" class="form-inline">
    {{csrf_field()}}
    <div class="form-group">
      <label class="sr-only" for="user_id">{{trans('wzoj.username')}}</label>
      <select class="selectpicker" data-live-search="true" name="user_id">
      @foreach ($users as $user)
        <option data-tokens="{{$user->id}} {{$user->fullname}} {{$user->name}}" value="{{$user->id}}">{{$user->name}}</option>
      @endforeach
      </select>
    </div>
    <div class="form-group">
      <label class="sr-only" for="role_id">{{trans('wzoj.role')}}</label>
      <select class="form-control" name="role_id">
      @foreach ($roles as $role)
	@if ($role->name !== 'admin')
	<option value="{{$role->id}}">{{trans('wzoj.rolename_'.$role->name)}}</option>
	@endif
      @endforeach
      </select>
    </div>
    <div class="form-group">
      <label class="sr-only" for="remark">{{trans('wzoj.remark')}}</label>
      <input type='text' class='form-control' name='remark' id='remark' placeholder="{{trans('wzoj.remark')}}" size="40">
    </div>
    <button type="submit" class="btn btn-default">{{trans('wzoj.assign_role')}}</button>
  </form>
</div>

<div class="col-xs-2">
  <form method="POST" action="/admin/cache-clear">
    {{csrf_field()}}
    <button type="submit" class="btn btn-warning">{{trans('wzoj.flush_cache')}}</button>
  </form>
</div>

<table class="table col-xs-12">
  <thead>
    <tr>
      <th style="width:30%">{{trans('wzoj.username')}}</th>
      <th style="width:20%">{{trans('wzoj.remark')}}</th>
      <th style="width:30%">{{trans('wzoj.role')}}</th>
      <th style="width:20%"></th>
    </tr>
  </thead>
  <tbody>
  @foreach ($users as $user)
    @foreach ($user->roles as $role)
      <tr>
        <td><a href="/users/{{$user->id}}">{{$user->name}}</a></td>
        <td>{{$role->pivot->remark}}</td>
	<td>{{trans('wzoj.rolename_'.$role->name)}}</td>
	<td>
	@if ($role->name !== 'admin')
	  <form method="POST">
	    {{csrf_field()}}
            {{method_field("DELETE")}}
	    <input hidden name="user_id" value="{{$user->id}}">
	    <input hidden name="role_id" value="{{$role->id}}">
	    <button type="submit" class="btn btn-danger">{{trans('wzoj.delete')}}</button>
	  </form>
	@endif
	</td>
      </tr>
    @endforeach
  @endforeach
  </tbody>
</table>
@endsection
