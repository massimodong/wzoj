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
  <a href='#' onclick="$('#problem_form').submit();"> [{{trans('wzoj.save')}}] </a>
</p>
<hr>

<ul class="nav nav-tabs" role="tablist">
  <li class="nav-item">
    <a class="nav-link active" id="basic-tab" data-toggle="tab" href="#basic" role="tab" aria-controls="basic" aria-selected="true"> {{trans('wzoj.problem_basic')}} </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="io_format-tab" data-toggle="tab" href="#io_format" role="tab" aria-controls="io_format" aria-selected="false"> {{trans('wzoj.io_format')}} </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="samples-tab" data-toggle="tab" href="#samples" role="tab" aria-controls="samples" aria-selected="false"> {{trans('wzoj.samples')}} </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="hints-tab" data-toggle="tab" href="#hints" role="tab" aria-controls="hints" aria-selected="false"> {{trans('wzoj.hints')}} </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="subtasks-tab" data-toggle="tab" href="#subtasks" role="tab" aria-controls="subtasks" aria-selected="false"> {{trans('wzoj.subtasks')}} </a>
  </li>
  <li class="nav-item">
    <a class="nav-link" id="others-tab" data-toggle="tab" href="#others" role="tab" aria-controls="others" aria-selected="false"> {{trans('wzoj.others')}} </a>
  </li>
</ul>

<div class="buffer-sm"></div>

<form method='POST' id='problem_form' class="form-horizontal">
  {{csrf_field()}}
  {{method_field('PUT')}}

<div class="tab-content">
  <div id="basic" class="tab-pane show active" role="tabpanel" aria-labelledby="basic-tab">
    <div class="form-group row">
      <label for="name" class="col-sm-2 col-form-label"> {{trans('wzoj.name')}} </label>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="name" name="name" value="{{$problem->name}}" required>
      </div>
    </div>

    <div class="form-group row">
      <label for="type" class="col-sm-2 col-form-label"> {{trans('wzoj.type')}} </label>
      <div class="col-sm-10">
        <select class="form-control" id="type" name="type">
          <option value="1" {{$problem->type==1?"selected":""}}> {{trans('wzoj.problem_type_1')}} </option>
          <option value="2" {{$problem->type==2?"selected":""}}> {{trans('wzoj.problem_type_2')}} </option>
          <option value="3" {{$problem->type==3?"selected":""}}> {{trans('wzoj.problem_type_3')}} </option>
        </select>
      </div>
    </div>

    <div class="form-group row">
      <div class="col-sm-offset-2 col-sm-10">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="spj" value="1" {{$problem->spj?"checked":""}}> {{trans('wzoj.spj')}}
          </label>
        </div>
      </div>
    </div>

    <div class="form-group row">
      <label for="timelimit" class="col-sm-2 col-form-label"> {{trans('wzoj.time_limit')}} (ms)</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="timelimit" name="timelimit" value="{{$problem->timelimit}}" required>
      </div>
    </div>

    <div class="form-group row">
      <label for="memorylimit" class="col-sm-2 col-form-label"> {{trans('wzoj.memory_limit')}} (MB)</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="memorylimit" name="memorylimit" value="{{$problem->memorylimit}}" required>
      </div>
    </div>

    <div class="form-group row">
      <label for="description" class="col-sm-2 col-form-label"> {{trans('wzoj.description')}} </label>
      <div class="col-sm-10">
        <textarea class='ojeditor' id='description' name='description'>{{htmlspecialchars($problem->description)}}</textarea>
      </div>
    </div>

  </div>
  <!-- basic -->

  <div id="io_format" class="tab-pane" role="tabpanel" aria-labelledby="io_format-tab">
    <div class="form-group row">
      <label for="inputformat" class="col-sm-2 col-form-label"> {{trans('wzoj.input_format')}} </label>
      <div class="col-sm-10">
        <textarea class='ojeditor' id='inputformat' name='inputformat'>{{htmlspecialchars($problem->inputformat)}}</textarea>
      </div>
    </div>

    <div class="form-group row">
      <label for="outputformat" class="col-sm-2 col-form-label"> {{trans('wzoj.output_format')}} </label>
      <div class="col-sm-10">
        <textarea class='ojeditor' id='outputformat' name='outputformat'>{{htmlspecialchars($problem->outputformat)}}</textarea>
      </div>
    </div>
  </div>
  <!-- io_format -->

  <div id="samples" class="tab-pane" role="tabpanel" aria-labelledby="samples-tab">
    <div class="form-group row">
      <label for="sampleinput" class="col-sm-2 col-form-label"> {{trans('wzoj.sample_input')}} </label>
      <div class="col-sm-10">
        <textarea class='form-control' id='sampleinput' name='sampleinput' rows="5">{{$problem->sampleinput}}</textarea>
      </div>
    </div>

    <div class="form-group row">
      <label for="sampleoutput" class="col-sm-2 col-form-label"> {{trans('wzoj.sample_output')}} </label>
      <div class="col-sm-10">
        <textarea class='form-control' id='sampleoutput' name='sampleoutput' rows="5">{{$problem->sampleoutput}}</textarea>
      </div>
    </div>
  </div>
  <!-- samples -->

  <div id="hints" class="tab-pane" role="tabpanel" aria-labelledby="hints-tab">
    <div class="form-group row">
      <label for="hint" class="col-sm-2 col-form-label"> {{trans('wzoj.hints')}} </label>
      <div class="col-sm-10">
        <textarea class='ojeditor' id='hint' name='hint'>{{htmlspecialchars($problem->hint)}}</textarea>
      </div>
    </div>

    <div class="form-group row">
      <label for="source" class="col-sm-2 col-form-label"> {{trans('wzoj.source')}} </label>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="source" name="source" value="{{$problem->source}}" required>
      </div>
    </div>
  </div>
  <!-- hints -->

  <div id="subtasks" class="tab-pane" role="tabpanel" aria-labelledby="subtasks-tab">
    <div class="form-group row">
      <div class="col-sm-offset-2 col-sm-10">
        <div class="checkbox">
          <label>
            <input type="checkbox" name="use_subtasks" value="1" {{$problem->use_subtasks?"checked":""}}> {{trans('wzoj.use_subtasks')}}
            <a href="https://github.com/massimodong/wzoj/wiki/Subtasks" target="_blank"> ({{trans('wzoj.help')}}) </a>
          </label>
        </div>
      </div>
    </div>

    <div class="form-group row">
      <label for="subtasks" class="col-sm-2 col-form-label"> {{trans('wzoj.subtasks')}} </label>
      <div class="col-sm-10">
        <textarea class='form-control' rows='20' id='subtasks' name='subtasks'>{{htmlspecialchars(json_encode($problem->subtasks, JSON_PRETTY_PRINT))}}</textarea>
      </div>
    </div>
  </div>
  <!-- subtasks -->

  <div id="others" class="tab-pane" role="tabpanel" aria-labelledby="others-tab">
    <div class="form-group row">
      <label for="source" class="col-sm-2 col-form-label"> {{trans('wzoj.tags')}} </label>
      <div class="col-sm-10">
        <select name="tags[]" class="selectpicker form-control" data-live-search="true" multiple>
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

    <div class="form-group row">
      <label for="tutorial" class="col-sm-2 col-form-label"> {{trans('wzoj.tutorial')}} </label>
      <div class="col-sm-10">
        <textarea class='ojeditor' id='tutorial' name='tutorial'>{{htmlspecialchars($problem->tutorial)}}</textarea>
      </div>
    </div>

    <div class="form-group row">
      <label for="remark" class="col-sm-2 col-form-label"> {{trans('wzoj.remark')}} </label>
      <div class="col-sm-10">
        <textarea class='form-control' id='remark' name='remark'>{{$problem->remark}}</textarea>
      </div>
    </div>
    @if (Auth::user()->has_role('admin'))
    <div class="form-group row">
      <label for="manager" class="col-sm-2 col-form-label"> {{trans('wzoj.manager')}} </label>
      <div class="col-sm-1">
        <input type="text" class="form-control" id="manager" name="manager" value="{{$problem->manager?$problem->manager->id:''}}">
      </div>
      <div class="col-sm-9">
      @if (isset($problem->manager))
        <a href="/users/{{$problem->manager->id}}">({{$problem->manager->name}})</a>
      @endif
      </div>
    </div>
    @endif
  </div>
  <!-- others -->

</div>
<!-- tab-content -->

</form>

<form method='POST'>
{{csrf_field()}}
{{method_field('DELETE')}}
<div class="col-sm-12">
  <!-- Can Not Delete -->
  <!-- <button type="submit" class="btn btn-danger"> {{trans('wzoj.delete')}} </button> -->
</div>
</form>

@endsection
