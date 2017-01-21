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
	    <th>
		<form action='/s/{{$problemset->id}}/{{$problem->id}}' method='POST'>
		{{csrf_field()}}
		{{method_field('PUT')}}
		<input name='newindex'><button>move to</button>
		</form>

		<form action='/s/{{$problemset->id}}/{{$problem->id}}' method='POST'>
		{{csrf_field()}}
		{{method_field('DELETE')}}
		<button>delete</button>
		</form>
	    </th>
	</tr>
    @endforeach
    <tbody>
    </table>

    <form action='/s/{{$problemset->id}}' method='POST'>
    {{csrf_field()}}
    <input name='pid'>
    <button>new problem</button>
</form>

</div>
<!-- problems -->

<div id="groups" class="tab-pane">
    @foreach ($problemset->groups as $group)
    {{$group->name}}
    @endforeach
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
</script>
@endsection
