@extends ('admin.layout')

@section ('title')
{{trans('wzoj.group')}}-{{$group->name}}
@endsection

@section ('content')
<ul class="nav nav-tabs" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="group-tab" data-toggle="tab" href="#group" role="tab" aria-controls="group" aria-selected="true"> {{trans('wzoj.groups')}} </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="users-tab" data-toggle="tab" href="#users" role="tab" aria-controls="users" aria-selected="false"> {{trans('wzoj.users')}} </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="homework-tab" data-toggle="tab" href="#homework" role="tab" aria-controls="homework" aria-selected="false"> {{trans('wzoj.homework')}} </a>
  </li>
</ul>

<div class="buffer-sm"></div>
<div class="tab-content">
  <div id="group" class="tab-pane fade show active" role="tabpanel" aria-labelledby="group-tab">
    <form method='POST' id='group_form'>
      {{csrf_field()}}
      {{method_field('PUT')}}
      <div class="form-group row">
        <label for="name" class="col-sm-2 col-form-label"> {{trans('wzoj.name')}} </label>
        <div class="col-sm-10">
          <input type='text' class='form-control' name='name' id='name' value='{{$group->name}}'>
        </div>
      </div>
      <div class="form-group row">
        <label for="notice" class="col-sm-2 col-form-label"> {{trans('wzoj.notice')}} </label>
        <div class="col-sm-10">
          <textarea class='form-control' name='notice' id='notice' rows='5'>{{$group->notice}}</textarea>
        </div>
      </div>
      @if (Auth::user()->has_role('admin'))
      <div class="form-group row">
        <label for="manager" class="col-sm-2 col-form-label"> {{trans('wzoj.manager')}} </label>
        <div class="col-sm-1">
          <input type='text' class='form-control' name='manager' id='manager' value='{{$group->manager?$group->manager->id:""}}'>
        </div>
        <div class="col-sm-9">
          @if (isset($group->manager))
            <a href="/users/{{$group->manager->id}}">{{$group->manager->name}}</a>
          @endif
        </div>
      </div>
      @endif
      <button type="submit" class="btn btn-primary">{{trans('wzoj.submit')}}</button>
    </form>
    <hr>

    <form method='POST'>
      {{csrf_field()}}
      {{method_field('DELETE')}}
      <button type="submit" class="btn btn-danger">{{trans('wzoj.delete')}}</button>
    </form>

  </div>
  <!-- group -->
  <div id="users" class="tab-pane fade" role="tabpanel" aria-labelledby="users-tab">
    <!-- Add Users -->
    <div class="row">
      <form action='/admin/groups/{{$group->id}}/users' method='POST' class="form-inline col-sm-4">
        {{csrf_field()}}
        <div class="form-group">
          <label for="uids" class="sr-only"></label>
          <select name="uids[]" id="uids" class="selectpicker" data-live-search="true" title="{{trans('wzoj.search_user')}}" multiple>
          @foreach (\App\User::orderBy('id', 'asc')->get(['id', 'name']) as $user)
            <option value="{{$user->id}}">{{$user->id}}-{{$user->name}}</option>
          @endforeach
          </select>
        </div>
        <button type="submit" class="btn btn-primary">{{trans('wzoj.new_user')}}</button>
      </form>
      <!-- operations -->
      <form id="users_form" action='/admin/groups/{{$group->id}}/users' method='POST' class="form-inline col-sm-1">
      {{csrf_field()}}
      </form>
      <div class="col-sm-7">
        <div class="dropdown">
          <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          {{trans('wzoj.operations')}}
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenu1">
            <a href="#" class="dropdown-item" onclick="users_action('lock_fullname');"> {{trans('wzoj.lock_fullname')}} </a>
            <a href="#" class="dropdown-item" onclick="users_action('lock_class');"> {{trans('wzoj.lock_class')}} </a>
            <a href="#" class="dropdown-item" onclick="users_action('unlock_fullname');"> {{trans('wzoj.unlock_fullname')}} </a>
            <a href="#" class="dropdown-item" onclick="users_action('unlock_class')();"> {{trans('wzoj.unlock_class')}} </a>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item" onclick="users_expel_from_group();" style="color: red"> {{trans('wzoj.expel_from_group')}} </a>
          </div>
        </div>
      </div>
    </div>

    <!-- the table -->
    <table id="users_table" class="table">
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

  <div id="homework" class="tab-pane fade" role="tabpanel" aria-labelledby="homework-tab">
    <form action='/admin/groups/{{$group->id}}/homeworks' method='POST' class="form-inline">
      {{csrf_field()}}
      <div class="form-group">
        <label for="psid" class="sr-only"></label>
        <select name="psid" id="psid" class="selectpicker" data-live-search="true" title="{{trans('wzoj.problemset')}}">
        @foreach (Auth::user()->problemsets() as $problemset)
          <option value="{{$problemset->id}}">{{$problemset->id}} - {{$problemset->name}}</option>
        @endforeach
        </select>

        <label for="pids" class="sr-only"></label>
        <select name="pids[]" id="pids" class="selectpicker" data-live-search="true" title="{{trans('wzoj.problem')}}" multiple>
        </select>
      </div>
      <button type="submit" class="btn btn-primary">{{trans('wzoj.new_homework')}}</button>
      <a href="/groups/{{$group->id}}/homework">{{trans('wzoj.homework_status')}}</a>
    </form>
    <div class="buffer-sm"></div>
    <ul class="list-group">
      @foreach ($group->homeworks as $problem)
        <li class="list-group-item">
          <div class="row">
            <span class="col-8"><a href="/s/{{$problem->pivot->problemset_id}}/{{$problem->id}}">{{$problem->name}}</a></span>
            <form class="col-4" action="/admin/groups/{{$group->id}}/homeworks" method="POST">
              {{csrf_field()}}
              {{method_field('DELETE')}}
              <input name="id[]" value="{{$problem->id}}" hidden>
              <button type="submit" class="btn btn-danger">{{trans('wzoj.terminate_homework')}}</button>
            </form>
          </div>
        </li>
      @endforeach
    </ul>
  </div>
  <!-- homework -->

</div>
@endsection

@section ('scripts')
<script>
function users_action( action ){
        $("#users_form").attr('action', '/users');
        $("#users_form").append('<input hidden name="_method" value="PUT">')
        $("#users_form").append('<input hidden name="action" value="' + action + '">')
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

$('#psid').on('changed.bs.select', function (e) {
        psid = $('#psid').val();
        $.get("/admin/ajax/problemset-problems", {problemset_id: psid}).done(function(data){
                $('#pids').html("");
                data.forEach(function(value, index, ar){
                        $('#pids').append("<option value='" + value.id + "'>" + value.id + "-" + escapeHtml(value.name) + "</option>");
                });
                $('#pids').selectpicker('refresh');
        });
});

var ids = [];
jQuery(document).ready(function($) {
        createDatatableWithCheckboxs("users_table", ids, "users_form");
});
</script>
@endsection
