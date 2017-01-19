@extends ('layouts.master')

@section ('title')
{{$problem->name}}
@endsection

@if ($problemset->type <> 'set')
@include ('layouts.contest_header')
@endif

@section ('content')

<ul class="nav nav-tabs" id="problemTabs">
  <li class="active"><a data-toggle="tab" href="#problem"> {{trans('wzoj.problem')}} </a></li>
  <li><a data-toggle="tab" href="#submit"> {{trans('wzoj.submit')}} </a></li>
</ul>
<div class="tab-content">
  <div id="problem" class="tab-pane fade in active">
    @include ('layouts.showproblem')
  </div>
  <!-- problem -->

  <div id="submit" class="tab-pane fade">
    <div class="top-buffer-sm"></div>
    <form action='/solutions' method='POST' enctype='multipart/form-data'>
      {{csrf_field()}}
      <input name='problemset_id' value='{{$problemset->id}}' hidden>
      <input name='problem_id' value='{{$problem->id}}' hidden>
      <div class='form-group row'>
        <label for='language' class='col-lg-1 col-form-label'> {{trans('wzoj.language')}}: </label>
	<div class='col-lg-3'>
          <select name='language' id='language' class='form-control input-sm'>
      	    <option value='0'>C</option>
	    <option value='1'>C++</option>
	    <option value='2'>Pascal</option>
          </select>
	</div>
	<label for='srcfile' class='col-lg-1 col-form-label'> {{trans('wzoj.choosefile')}}</label>
	<div class='col-lg-7'>
	  <input type="file" class="file" name="srcfile" id="srcfile">
	</div>
      </div>
      <!-- form-group -->

      <div class='form-group'>
        <textarea class="form-control" name="code" id="code" rows="3">{{old('code')}}</textarea>
      </div>
      <!-- form-group -->

      <button type="submit" class="btn btn-primary"> {{trans('wzoj.submit')}} </button>
    </form>
  </div>
  <!-- submit -->
</div>
<!-- tab-content -->

@endsection

@section ('scripts')
@if (count($errors) > 0)
<script>
jQuery(document).ready(function($) {
	$('#problemTabs a[href="#submit"]').tab('show');
});
</script>
@endif
@endsection
