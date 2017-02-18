@extends ('admin.layout')

@section ('title')
{{trans('wzoj.group')}}-{{$group->name}}
@endsection

@section ('content')
<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#group"> {{trans('wzoj.groups')}} </a></li>
  <li><a data-toggle="tab" href="#users"> {{trans('wzoj.users')}} </a></li>
</ul>

<div class="top-buffer-sm"></div>
<div class="tab-content">
  <div id="group" class="tab-pane in active">
    <form method='POST' id='group_form' class="form-horizontal">
      {{csrf_field()}}
      {{method_field('PUT')}}
      <div class="form-group">
        <label for="name" class="col-xs-2 control-label"> {{trans('wzoj.name')}} </label>
	<div class="col-xs-10">
          <input type='text' class='form-control' name='name' id='name' value='{{$group->name}}'>
	</div>
      </div>
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
	  <button type="submit" class="btn btn-default">{{trans('wzoj.submit')}}</button>
        </div>
      </div>
    </form>
    <hr>

    <form method='POST' class="form-horizontal">
      {{csrf_field()}}
      {{method_field('DELETE')}}
      <div class="form-group">
        <div class="col-sm-offset-2 col-sm-10">
	  <button type="submit" class="btn btn-danger">{{trans('wzoj.delete')}}</button>
        </div>
      </div>
    </form>

  </div>
  <!-- group -->
  <div id="users" class="tab-pane">
    <!-- Add Users -->
    <form action='/admin/groups/{{$group->id}}/users' method='POST' class="form-inline col-xs-6">
      {{csrf_field()}}
      <div class="form-group">
        <label for="uids" class="sr-only"></label>
        <select name="uids[]" id="uids" class="selectpicker" data-live-search="true" title="{{trans('wzoj.search_user')}}" multiple>
        @foreach (\App\User::orderBy('id', 'asc')->get() as $user)
          <option value="{{$user->id}}">{{$user->id}}-{{$user->name}}</option>
        @endforeach
        </select>
      </div>
      <button type="submit" class="btn btn-primary">{{trans('wzoj.new_user')}}</button>
    </form>
    <!-- operations -->
    <form id="users_form" action='/admin/groups/{{$group->id}}/users' method='POST' class="form-inline col-xs-1">
    {{csrf_field()}}
    </form>
    <div class="col-xs-5">
      <div class="dropdown">
        <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
        {{trans('wzoj.operations')}}
        <span class="caret"></span>
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
          <li><a href="#" onclick="users_lock_fullname();"> {{trans('wzoj.lock_fullname')}} </a></li>
	  <li><a href="#" onclick="users_lock_class();"> {{trans('wzoj.lock_class')}} </a></li>
	  <li><a href="#" onclick="users_unlock_fullname();"> {{trans('wzoj.unlock_fullname')}} </a></li>
	  <li><a href="#" onclick="users_unlock_class();"> {{trans('wzoj.unlock_class')}} </a></li>
	  <li role="separator" class="divider"></li>
	  <li><a href="#" onclick="users_expel_from_group();" style="color: red"> {{trans('wzoj.expel_from_group')}} </a></li>
        </ul>
      </div>
    </div>

    <!-- the table -->
    <table id="users_table" class="table table-striped">
    <thead>
      <tr>
        <th style="width: 1%;"><input name="select_all" value="1" type="checkbox"></th>
        <th style="width: 5%;"> {{trans('wzoj.id')}} </th>
        <th> {{trans('wzoj.username')}} </th>
        <th> {{trans('wzoj.fullname')}} </th>
        <th> {{trans('wzoj.class')}} </th>
      </tr>
    </thead>
    <tbody>
    @foreach ($group->users as $user)
      <tr>
        <td></td>
        <td>{{$user->id}}</td>
        <td><a href="/users/{{$user->id}}">{{$user->name}}</a></td>
        <td>
	@if ($user->fullname_lock)
	<span class="glyphicon glyphicon-lock"></span>
	@endif
	{{$user->fullname}}
	</td>
        <td>
	@if ($user->class_lock)
	<span class="glyphicon glyphicon-lock"></span>
	@endif
	{{$user->class}}
	</td>
      </tr>
    @endforeach
    </tbody>
    </table>
  </div>
  <!-- users -->

</div>
@endsection

@section ('scripts')
<script>
function users_lock_fullname(){
	$("#users_form").attr('action', '/users');
	$("#users_form").append('<input hidden name="_method" value="PUT">')
	$("#users_form").append('<input hidden name="action" value="lock_fullname">')
	//submit
	var submitInput = $("<input type='submit' />");
	$("#users_form").append(submitInput);
	submitInput.trigger("click");
}

function users_lock_class(){
	$("#users_form").attr('action', '/users');
	$("#users_form").append('<input hidden name="_method" value="PUT">')
	$("#users_form").append('<input hidden name="action" value="lock_class">')
	//submit
	var submitInput = $("<input type='submit' />");
	$("#users_form").append(submitInput);
	submitInput.trigger("click");
}

function users_unlock_fullname(){
	$("#users_form").attr('action', '/users');
	$("#users_form").append('<input hidden name="_method" value="PUT">')
	$("#users_form").append('<input hidden name="action" value="unlock_fullname">')
	//submit
	var submitInput = $("<input type='submit' />");
	$("#users_form").append(submitInput);
	submitInput.trigger("click");
}

function users_unlock_class(){
	$("#users_form").attr('action', '/users');
	$("#users_form").append('<input hidden name="_method" value="PUT">')
	$("#users_form").append('<input hidden name="action" value="unlock_class">')
	//submit
	var submitInput = $("<input type='submit' />");
	$("#users_form").append(submitInput);
	submitInput.trigger("click");
}
function users_expel_from_group(){
	$("#users_form").append('<input hidden name="_method" value="DELETE">')
	//submit
	var submitInput = $("<input type='submit' />");
	$("#users_form").append(submitInput);
	submitInput.trigger("click");
}

selectHashTab();
var ids = [];
jQuery(document).ready(function($) {
	createDatatableWithCheckboxs("users_table", ids, "users_form");
});
</script>
@endsection
