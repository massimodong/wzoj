@extends ('admin.layout')

@section ('title')
{{trans('wzoj.problem_rejudge')}}
@endsection

@section ('content')
<form method='POST'>
{{csrf_field()}}

<div class="form-group">
  <label for="solution_id"> {{trans('wzoj.solution_id')}} </label>
  <input type="text" class="form-control" id="solution_id" name="solution_id">
</div>

<div class="form-group">
  <label for="problemset_id"> {{trans('wzoj.or')}} {{trans('wzoj.problemset')}} </label>
  <select name="problemset_id" id="problemset_id" class="selectpicker">
  	<option disabled selected value style="display:none"></option>
    @foreach (\App\Problemset::all() as $problemset)
	<option value="{{$problemset->id}}">{{$problemset->id}}-{{$problemset->name}}</option>
    @endforeach
  </select>
</div>

<div class="form-group">
  <label for="problem_id"> {{trans('wzoj.or')}} {{trans('wzoj.problem')}} </label>
  <select name="problem_id" id="problem_id" class="selectpicker" data-live-search="true">
  	<option disabled selected value style="display:none"></option>
    @foreach (\App\Problem::all() as $problem)
	<option value="{{$problem->id}}">{{$problem->id}}-{{$problem->name}}</option>
    @endforeach
  </select>
</div>

<button type="submit" class="btn btn-default"> {{trans('wzoj.submit')}} </button>

<p class="help-block">{{trans('wzoj.msg_problem_rejudge_helper')}}</p>

</form>
@endsection
