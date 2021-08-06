@extends ('admin.layout')

@section ('title')
{{trans('wzoj.edit')}} {{$problemset->name}}
@endsection

@section ('content')

<ul class="nav nav-tabs">
  <li class="nav-item">
    <a class="nav-link active" id="problemset-tab" data-toggle="tab" href="#problemset" role="tab" aria-controls="problemset" aria-selected="true">{{trans('wzoj.problemset')}}</a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="problems-tab" data-toggle="tab" href="#problems" role="tab" aria-controls="problems" aria-selected="false">{{trans('wzoj.problems')}}</a>
  </li>
  <li>
    <a class="nav-link" id="groups-tab" data-toggle="tab" href="#groups" role="tab" aria-controls="groups" aria-selected="false">{{trans('wzoj.groups')}}</a>
  </li>
</ul>

<div class="tab-content">

<div id="problemset" class="tab-pane fade show active" role="tabpanel" aria-labelledby="problemset-tab">
  <form action='/s/{{$problemset->id}}' method='POST'>
    {{csrf_field()}}
    {{method_field('PUT')}}

    <div class="form-group row">
      <label class="col-form-label col-sm-2" for="name">{{trans('wzoj.name')}}:</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" name='name' id="name" value='{{$problemset->name}}'>
      </div>
    </div>
    <div class="form-group row">
      <label class="col-form-label col-sm-2" for="type">{{trans('wzoj.type')}}:</label>
      <div class="col-sm-10">
        <select class="form-control" name="type" id="type">
          <option value="set" {{$problemset->type=="set"?"selected":""}}>{{trans('wzoj.problem_type_set')}}</option>
          <option value="oi" {{$problemset->type=="oi"?"selected":""}}>{{trans('wzoj.problem_type_oi')}}</option>
          <option value="acm" {{$problemset->type=="acm"?"selected":""}}>{{trans('wzoj.problem_type_acm')}}</option>
          <option value="apio" {{$problemset->type=="apio"?"selected":""}}>{{trans('wzoj.problem_type_apio')}}</option>
        </select>
      </div>
    </div>
    <div class="form-group row">
      <label class="col-form-label col-sm-2" for="contest_start_at">{{trans('wzoj.contest_start_at')}}:</label>
      <div class="col-sm-10">
        <input type="text" class="form-control datetimepicker-input" name='contest_start_at' id="contest_start_at" data-toggle="datetimepicker" data-target="#contest_start_at" data-date-format="YYYY-MM-DD HH:mm:ss">
      </div>
    </div>
    <div class="form-group row">
      <label class="col-form-label col-sm-2" for="contest_end_at">{{trans('wzoj.contest_end_at')}}:</label>
      <div class="col-sm-10">
        <input type="text" class="form-control datetimepicker-input" name='contest_end_at' id="contest_end_at" data-toggle="datetimepicker" data-target="#contest_end_at" data-date-format="YYYY-MM-DD HH:mm:ss">
      </div>
    </div>
    <div class="form-group row">
      <div class="col-sm-offset-2 col-sm-10">
        <div class="custom-control custom-radio custom-control-inline">
          <input type="radio" id="participate_type_radio_0" name="participate_type" value="0" class="custom-control-input" @if ($problemset->participate_type == 0) checked @endif>
          <label class="custom-control-label" for="participate_type_radio_0">{{trans('wzoj.participate_type_standard')}}</label>
        </div>
        <div class="custom-control custom-radio custom-control-inline">
          <input type="radio" id="participate_type_radio_1" name="participate_type" value="1" class="custom-control-input" @if ($problemset->participate_type == 1) checked @endif>
          <label class="custom-control-label" for="participate_type_radio_1">{{trans('wzoj.participate_type_duration')}}</label>
        </div>
        <input type="text" name="contest_duration" value="{{$problemset->contest_duration}}">
        <div class="custom-control custom-radio custom-control-inline">
          <input type="radio" id="participate_type_radio_2" name="participate_type" value="2" class="custom-control-input" disabled @if ($problemset->participate_type == 2) checked @endif>
          <label class="custom-control-label" for="participate_type_radio_2">{{trans('wzoj.participate_type_allow_virtual')}}</label>
        </div>
      </div>
    </div>

    <div class="form-group row">
      <label class="col-form-label col-sm-2" for="tag">{{trans('wzoj.tag')}}:</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" name='tag' id="tag" value='{{$problemset->tag}}'>
      </div>
    </div>

    <div class="form-group row">
      <div class="col-sm-offset-2 col-sm-1">
        <div class="checkbox">
          <label><input type="checkbox" name='public' value='1' {{$problemset->public?"checked":""}}>{{trans('wzoj.public')}}</label>
        </div>
      </div>
      <div class="col-sm-2">
        <div class="checkbox">
          <label><input type="checkbox" name='show_problem_tags' value='1' {{$problemset->show_problem_tags?"checked":""}}>{{trans('wzoj.show_problem_tags')}}</label>
        </div>
      </div>
      <div class="col-sm-2">
        <div class="checkbox">
          <label><input type="checkbox" name='show_tutorial' value='1' {{$problemset->show_tutorial?"checked":""}}>{{trans('wzoj.show_tutorial')}}</label>
        </div>
      </div>
      <div class="col-sm-5">
        <div class="checkbox">
          <label><input type="checkbox" name='contest_hide_solutions' value='1' {{$problemset->contest_hide_solutions?"checked":""}}>{{trans('wzoj.contest_hide_solutions')}}</label>
        </div>
      </div>
    </div>
    <div class="form-group row">
      <label class="col-form-label col-sm-2" for="description">{{trans('wzoj.description')}}:</label>
      <div class="col-sm-10">
        <textarea class="form-control ojeditor" name="description" id="description">{{$problemset->description}}</textarea>
      </div>
    </div>
    <div class="form-group row">
      <label class="col-form-label col-sm-2" for="remark">{{trans('wzoj.remark')}}:</label>
      <div class="col-sm-10">
        <textarea class="form-control" name="remark" id="remark">{{$problemset->remark}}</textarea>
      </div>
    </div>

    @if (Auth::user()->has_role('admin'))
    <div class="form-group row">
      <label class="col-form-label col-sm-2" for="tag">{{trans('wzoj.manager')}}:</label>
      <div class="col-sm-1">
        <input type="text" class="form-control" name='manager' id="manager" value='{{$problemset->manager?$problemset->manager->id:''}}'>
      </div>
      <div class="col-xs-9">
      @if (isset($problemset->manager))
        <a href="/users/{{$problemset->manager->id}}">{{$problemset->manager->name}}</a>
      @endif
      </div>
    </div>
    @endif
    <button type="submit" class="btn btn-primary"> {{trans('wzoj.submit')}} </button>
  </form>
</div>
<!-- problemset -->

<div id="problems" class="tab-pane fade" role="tabpanel" aria-labelledby="problems-tab">
  <div class="row">
    <form action='/s/{{$problemset->id}}/problems' method='POST' class="form-inline col-sm-6">
      {{csrf_field()}}
      <div class="form-group">
        <label for="pids" class="sr-only"></label>
        <select name="pids[]" id="pids" class="selectpicker" data-live-search="true" title="{{trans('wzoj.search_problem')}}" multiple>
        @foreach (Auth::user()->manage_problems()->orderBy('id', 'desc')->get() as $problem)
          <option value="{{$problem->id}}">{{$problem->id}}-{{$problem->name}}</option>
        @endforeach
        </select>
      </div>
      <button type="submit" class="btn btn-primary">{{trans('wzoj.new_problem')}}</button>
    </form>
    <form action='/s/{{$problemset->id}}/problems' id="problems_form" method="POST" class="form-inline col-sm-6">
      {{csrf_field()}}
      <div class="form-group">
        <label for="newindex"> {{trans('wzoj.operations')}}: </label>
        <input type="text" name="newindex" id="newindex" size="2" class="form-control" placeholder="{{trans('wzoj.new_index')}}">
      </div>
      <button type="submit" class="btn btn-primary" onclick='$("#problems_form").append("<input hidden name=\"_method\" value=\"PUT\">")'>{{trans('wzoj.move_to')}}</button>
      <button type="submit" class="btn btn-danger" onclick='$("#problems_form").append("<input hidden name=\"_method\" value=\"DELETE\">")'>{{trans('wzoj.delete')}}</button>
    </form>
  </div>

  <table id="problems_table" class="table table-striped">
    <thead>
      <tr>
        <th style="width: 1%;"><input name="select_all" value="1" type="checkbox"></th>
        <th style="width: 5%;">{{trans('wzoj.index')}}</th>
        <th>{{trans('wzoj.problem')}}</th>
      </tr>
    </thead>
    <tbody>
    @foreach ($problems as $problem)
    <tr>
      <td></td>
      <td>{{$problem->pivot->index}}</td>
      <td><a href='/s/{{$problemset->id}}/{{$problem->id}}'>{{$problem->id}}-{{$problem->name}}</a>
        <span class="pull-right">
          @include ('partials.problem_tags', ['problem' => $problem])
        </span>
      </td>
    </tr>
    @endforeach
    <tbody>
  </table>
</div>
<!-- problems -->

<div id="groups" class="tab-pane fade" role="tabpanel" aria-labelledby="groups-tab">
  <form method="POST" action="/s/{{$problemset->id}}/groups" class="form-inline">
    {{csrf_field()}}
    <div class="form-group">
      <label for="gids">{{trans ('wzoj.choose_group')}}:</label>
      <select name="gid" id="gid" class="selectpicker" data-live-search="true">
      @foreach ($groups as $group)
        <option value="{{$group->id}}">{{$group->id}}-{{$group->name}}</option>
      @endforeach
      </select>
    </div>
    <button type="submit" class="btn btn-primary">{{trans('wzoj.add_group')}}</button>
  </form>
  <div class="buffer-sm"></div>
  <ul class="list-group">
  @foreach ($problemset->groups as $group)
    <li class="list-group-item"> {{$group->name}} <span class="pull-right"><a href="#" onclick="removeGroup({{$group->id}});return false;">{{trans('wzoj.delete')}} </a></span></li>
  @endforeach
  </ul>
</div>
<!-- groups -->

</div>
<!-- tab-content -->
@endsection

@section ('scripts')
<script>
jQuery(document).ready(function($) {
  $('#contest_start_at').datetimepicker();
  $('#contest_end_at').datetimepicker();
  $('#contest_start_at').datetimepicker('date', '{{$problemset->contest_start_at}}');
  $('#contest_end_at').datetimepicker('date', '{{$problemset->contest_end_at}}');
  var ids = [];
  createDatatableWithCheckboxs("problems_table", ids, "problems_form");
});
function removeGroup(id){
  $.post('/s/{{$problemset->id}}/groups/' + id,{
    _token: '{{csrf_token()}}',
    _method: 'DELETE'
    })
  .done(function(){
    location.reload();
  });
}
</script>
@endsection
