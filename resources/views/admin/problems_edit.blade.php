@extends ('admin.layout')

@section ('title')
{{$problem->name}}
@endsection

@section ('sidebar')
@parent
<li><a href='#' onclick="sendForm($('#problem_form')); return false;"> {{trans('wzoj.save')}} </a></li>
@endsection

@section ('content')

<p>
  <a href='/admin/problems/{{$problem->id}}?preview'> [{{trans('wzoj.preview')}}] </a>
  <a href='/admin/problems/{{$problem->id}}/data'> [{{trans('wzoj.testdata')}}] </a>
</p>
<hr>

<form method='POST' id='problem_form' class="form-horizontal">
  {{csrf_field()}}
  {{method_field('PUT')}}

  <div class="form-group">
    <label for="name" class="col-xs-2 control-label"> {{trans('wzoj.name')}} </label>
    <div class="col-xs-10">
      <input type="text" class="form-control" id="name" name="name" value="{{$problem->name}}" required>
    </div>
  </div>

  <div class="form-group">
    <label for="type" class="col-xs-2 control-label"> {{trans('wzoj.type')}} </label>
    <div class="col-xs-10">
      <select class="form-control" id="type" name="type">
        <option value="1" {{$problem->type==1?"selected":""}}> {{trans('wzoj.problem_type_1')}} </option>
        <option value="2" {{$problem->type==2?"selected":""}}> {{trans('wzoj.problem_type_2')}} </option>
        <option value="3" {{$problem->type==3?"selected":""}}> {{trans('wzoj.problem_type_3')}} </option>
      </select>
    </div>
  </div>

  <div class="form-group">
    <div class="col-sm-offset-2 col-sm-10">
      <div class="checkbox">
        <label>
          <input type="checkbox" name="spj" value="1" {{$problem->spj?"checked":""}}> {{trans('wzoj.spj')}}
        </label>
      </div>
    </div>
  </div>

  <div class="form-group">
    <label for="timelimit" class="col-xs-2 control-label"> {{trans('wzoj.time_limit')}} (ms)</label>
    <div class="col-xs-10">
      <input type="text" class="form-control" id="timelimit" name="timelimit" value="{{$problem->timelimit}}" required>
    </div>
  </div>

  <div class="form-group">
    <label for="memorylimit" class="col-xs-2 control-label"> {{trans('wzoj.memory_limit')}} (MB)</label>
    <div class="col-xs-10">
      <input type="text" class="form-control" id="memorylimit" name="memorylimit" value="{{$problem->memorylimit}}" required>
    </div>
  </div>

  <div class="form-group">
    <label for="description" class="col-xs-2 control-label"> {{trans('wzoj.description')}} </label>
    <div class="col-xs-10">
      <textarea class='ojeditor' id='description' name='description'>{{$problem->description}}</textarea>
    </div>
  </div>

  <div class="form-group">
    <label for="inputformat" class="col-xs-2 control-label"> {{trans('wzoj.input_format')}} </label>
    <div class="col-xs-10">
      <textarea class='ojeditor' id='inputformat' name='inputformat'>{{$problem->inputformat}}</textarea>
    </div>
  </div>

  <div class="form-group">
    <label for="outputformat" class="col-xs-2 control-label"> {{trans('wzoj.output_format')}} </label>
    <div class="col-xs-10">
      <textarea class='ojeditor' id='outputformat' name='outputformat'>{{$problem->outputformat}}</textarea>
    </div>
  </div>

  <div class="form-group">
    <label for="sampleinput" class="col-xs-2 control-label"> {{trans('wzoj.sample_input')}} </label>
    <div class="col-xs-10">
      <textarea class='form-control' id='sampleinput' name='sampleinput' rows="5">{{$problem->sampleinput}}</textarea>
    </div>
  </div>

  <div class="form-group">
    <label for="sampleoutput" class="col-xs-2 control-label"> {{trans('wzoj.sample_output')}} </label>
    <div class="col-xs-10">
      <textarea class='form-control' id='sampleoutput' name='sampleoutput' rows="5">{{$problem->sampleoutput}}</textarea>
    </div>
  </div>

  <div class="form-group">
    <label for="hint" class="col-xs-2 control-label"> {{trans('wzoj.hints')}} </label>
    <div class="col-xs-10">
      <textarea class='ojeditor' id='hint' name='hint'>{{$problem->hint}}</textarea>
    </div>
  </div>

  <div class="form-group">
    <label for="source" class="col-xs-2 control-label"> {{trans('wzoj.source')}} </label>
    <div class="col-xs-10">
      <input type="text" class="form-control" id="source" name="source" value="{{$problem->source}}" required>
    </div>
  </div>
  <div class="form-group">
    <label for="source" class="col-xs-2 control-label"> {{trans('wzoj.tags')}} </label>
    <div class="col-xs-10">
      <select name="tags[]" class="selectpicker" data-live-search="true" multiple>
        @foreach (\App\ProblemTag::all() as $tag)
	  <option data-tokens="{{$tag->aliases}}" value="{{$tag->id}}"
	    @if (isset($selected_tags[$tag->id]))
	      selected = "selected"
	    @endif
	  >{{$tag->name}}</option>
	@endforeach
      </select>
    </div>
  </div>

  <div class="form-group">
    <label for="tutorial" class="col-xs-2 control-label"> {{trans('wzoj.tutorial')}} </label>
    <div class="col-xs-10">
      <textarea class='ojeditor' id='tutorial' name='tutorial'>{{$problem->tutorial}}</textarea>
    </div>
  </div>

  <div class="form-group">
    <label for="remark" class="col-xs-2 control-label"> {{trans('wzoj.remark')}} </label>
    <div class="col-xs-10">
      <textarea class='form-control' id='remark' name='remark'>{{$problem->remark}}</textarea>
    </div>
  </div>

</form>

<form method='POST'>
{{csrf_field()}}
{{method_field('DELETE')}}
<div class="col-xs-12">
  <!-- Can Not Delete -->
  <!-- <button type="submit" class="btn btn-danger"> {{trans('wzoj.delete')}} </button> -->
</div>
</form>

@endsection
