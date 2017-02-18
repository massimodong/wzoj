@extends ('layouts.master')

@section ('title')
{{trans('wzoj.edit')}} {{$problemset->name}}
@endsection

@section ('sidebar')
@parent
<li><a href='#' onclick="sendForm($('#problemset_form')); return false;"> {{trans('wzoj.save')}} </a></li>
@endsection

@section ('content')

<ul class="nav nav-tabs">
  <li class="active"><a data-toggle="tab" href="#problemset">{{trans('wzoj.problemset')}}</a></li>
  <li><a data-toggle="tab" href="#problems">{{trans('wzoj.problems')}}</a></li>
  <li><a data-toggle="tab" href="#groups">{{trans('wzoj.groups')}}</a></li>
</ul>

<div class="tab-content">
<div class="top-buffer-sm"></div>

<div id="problemset" class="tab-pane in active">
    <form class='form-horizontal' id='problemset_form' action='/s/{{$problemset->id}}' method='POST'>
    {{csrf_field()}}
    {{method_field('PUT')}}

    <div class="form-group">
          <label class="control-label col-sm-2" for="name">{{trans('wzoj.name')}}:</label>
	  <div class="col-sm-10">
	        <input type="text" class="form-control" name='name' id="name" value='{{$problemset->name}}'>
	  </div>
    </div>
    <div class="form-group">
          <label class="control-label col-sm-2" for="type">{{trans('wzoj.type')}}:</label>
	  <div class="col-sm-10">
	  <!--      <input type="text" class="form-control" name='type' id="type" value='{{$problemset->type}}'> -->
		<select class="form-control" name="type" id="type">
		  <option value="set" {{$problemset->type=="set"?"selected":""}}>{{trans('wzoj.problem_type_set')}}</option>
		  <option value="oi" {{$problemset->type=="oi"?"selected":""}}>{{trans('wzoj.problem_type_oi')}}</option>
		  <option value="acm" {{$problemset->type=="acm"?"selected":""}}>{{trans('wzoj.problem_type_acm')}}</option>
		  <option value="apio" {{$problemset->type=="apio"?"selected":""}}>{{trans('wzoj.problem_type_apio')}}</option>
		</select>
	  </div>
    </div>
    <div class="form-group">
          <label class="control-label col-sm-2" for="contest_start_at">{{trans('wzoj.contest_start_at')}}:</label>
	  <div class="col-sm-10">
	        <input type="text" class="form-control" name='contest_start_at' id="contest_start_at" value='{{$problemset->contest_start_at}}' data-date-format="yyyy-mm-dd hh:ii">
	  </div>
    </div>
    <div class="form-group">
          <label class="control-label col-sm-2" for="contest_end_at">{{trans('wzoj.contest_end_at')}}:</label>
	  <div class="col-sm-10">
	        <input type="text" class="form-control" name='contest_end_at' id="contest_end_at" value='{{$problemset->contest_end_at}}' data-date-format="yyyy-mm-dd hh:ii">
	  </div>
    </div>
    <div class="form-group">        
          <div class="col-sm-offset-2 col-sm-10">
	      <div class="checkbox">
                  <label><input type="checkbox" name='public' value='1' {{$problemset->public?"checked":""}}>{{trans('wzoj.public')}}</label>
	      </div>
	  </div>
    </div>
    <div class="form-group">
          <label class="control-label col-sm-2" for="description">{{trans('wzoj.description')}}:</label>
	  <div class="col-sm-10">
	      <textarea class="form-control ojeditor" name="description" id="description">{{$problemset->description}}</textarea>
	  </div>
    </div>
    </form>

    <form action='/s/{{$problemset->id}}' method='POST' class="form-horizontal">
    {{csrf_field()}}
    {{method_field('DELETE')}}
    <div class="form-group"> 
        <div class="col-sm-offset-2 col-sm-10">
	    <button type="submit" class="btn btn-danger">{{trans('wzoj.delete')}}</button>
        </div>
    </div>
    </form>
</div>
<!-- problemset -->

<div id="problems" class="tab-pane">
    <form action='/s/{{$problemset->id}}/problems' method='POST' class="form-inline col-xs-6">
        {{csrf_field()}}
	<div class="form-group">
	  <label for="pids" class="sr-only"></label>
	  <select name="pids[]" id="pids" class="selectpicker" data-live-search="true" title="{{trans('wzoj.search_problem')}}" multiple>
	  @foreach (\App\Problem::orderBy('id', 'desc')->get() as $problem)
	    <option value="{{$problem->id}}">{{$problem->id}}-{{$problem->name}}</option>
	  @endforeach
	  </select>
	</div>
	<button type="submit" class="btn btn-primary">{{trans('wzoj.new_problem')}}</button>
    </form>
    <form action='/s/{{$problemset->id}}/problems' id="problems_form" method="POST" class="form-inline col-xs-6">
      {{csrf_field()}}
      <div class="form-group">
	<label for="newindex"> {{trans('wzoj.operations')}}: </label>
	<input type="text" name="newindex" id="newindex" size="2" class="form-control" placeholder="{{trans('wzoj.new_index')}}">
	</div>
	<button type="submit" class="btn btn-primary" onclick='$("#problems_form").append("<input hidden name=\"_method\" value=\"PUT\">")'>{{trans('wzoj.move_to')}}</button>
	<button type="submit" class="btn btn-danger" onclick='$("#problems_form").append("<input hidden name=\"_method\" value=\"DELETE\">")'>{{trans('wzoj.delete')}}</button>
    </form>

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
	    <td><a href='/s/{{$problemset->id}}/{{$problem->id}}'>{{$problem->id}}-{{$problem->name}}</a></td>
	</tr>
    @endforeach
    <tbody>
    </table>
</div>
<!-- problems -->

<div id="groups" class="tab-pane">
  <form method="POST" action="/s/{{$problemset->id}}/groups" class="form-inline">
    {{csrf_field()}}
    <div class="form-group">
      <label for="gids">{{trans ('wzoj.choose_group')}}:</label>
      <select name="gid" id="gid" class="selectpicker">
	@foreach ($groups as $group)
	<option value="{{$group->id}}">{{$group->name}}</option>
	@endforeach
      </select>
    </div>
    <button type="submit" class="btn btn-primary">{{trans('wzoj.add_group')}}</button>
  </form>
  <div class="top-buffer-sm"></div>
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
	$('#contest_start_at').datetimepicker({
		language: 'zh-CN'
	});
	$('#contest_end_at').datetimepicker({
		language: 'zh-CN'
	});
	var ids = [];
	createDatatableWithCheckboxs("problems_table", ids, "problems_form");
});
selectHashTab();
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
