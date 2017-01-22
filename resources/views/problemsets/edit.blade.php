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
	        <input type="text" class="form-control" name='type' id="type" value='{{$problemset->type}}'>
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
    <form action='/s/{{$problemset->id}}' method='POST' class="form-inline">
        {{csrf_field()}}
	<div class="form-group">
	  <label for="pid" class="sr-only"></label>
	  <select name="pid" id="pid" class="selectpicker" data-live-search="true" title="{{trans('wzoj.search_problem')}}">
	  @foreach (\App\Problem::get() as $problem)
	    <option value="{{$problem->id}}">{{$problem->id}}-{{$problem->name}}</option>
	  @endforeach
	  </select>
	</div>
	<button type="submit" class="btn btn-primary">{{trans('wzoj.new_problem')}}</button>
    </form>
    <div class="top-buffer-sm"></div>

    <table id="problems_table" class="table table-striped">
    <thead>
        <tr>
	    <th style="width: 5%;">{{trans('wzoj.index')}}</th>
	    <th>{{trans('wzoj.problem')}}</th>
	    <th>{{trans('wzoj.operations')}}</th>
	</tr>
    </thead>
    <tbody>
    @foreach ($problems as $problem)
	<tr>
	    <th>{{$problem->pivot->index}}</th>
	    <th><a href='/s/{{$problemset->id}}/{{$problem->id}}'>{{$problem->name}}</a></th>
	    <th class="row">
		<form action='/s/{{$problemset->id}}/{{$problem->id}}' method='POST' class="form-inline col-lg-10">
		  {{csrf_field()}}
		  {{method_field('PUT')}}
		  <div class="form-group">
		    <label for="newindex" class="sr-only">Move to</label>
		    <input type="text" name="newindex" id="newindex" size="2" class="form-control" placeholder="{{trans('wzoj.new_index')}}" required>
		  </div>
		  <button type="submit" class="btn btn-primary">{{trans('wzoj.move_to')}}</button>
		</form>

		<form action='/s/{{$problemset->id}}/{{$problem->id}}' method='POST' class="form-inline col-lg-2">
		  {{csrf_field()}}
		  {{method_field('DELETE')}}
		  <button type="submit" class="btn btn-danger">{{trans('wzoj.delete')}}</button>
		</form>
	    </th>
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
      <select name="gids[]" id="gids" class="selectpicker" multiple>
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
	$('#problems_table').dataTable({
		"autoWidth": false
	});
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
