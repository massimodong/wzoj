@extends ('admin.layout')

@section ('title')
{{trans('wzoj.problem_rejudge')}}
@endsection

@section ('content')
<form id='problem_rejudge_form'>
{{csrf_field()}}

<div class="form-group">
  <label for="solution_id"> {{trans('wzoj.solution_id')}} </label>
  <input type="text" class="form-control" id="solution_id" name="solution_id">
</div>

<div class="form-group">
  <label for="problemset_id"> {{trans('wzoj.problemset')}} </label>
  <select name="problemset_id" id="problemset_id" class="selectpicker">
  	<option disabled selected value style="display:none"></option>
    @foreach (\App\Problemset::all() as $problemset)
	<option value="{{$problemset->id}}">{{$problemset->id}}-{{$problemset->name}}</option>
    @endforeach
  </select>
</div>

<div class="form-group">
  <label for="problem_id"> {{trans('wzoj.problem')}} </label>
  <select name="problem_id" id="problem_id" class="selectpicker" data-live-search="true">
  	<option disabled selected value style="display:none"></option>
    @foreach (\App\Problem::all() as $problem)
	<option value="{{$problem->id}}">{{$problem->id}}-{{$problem->name}}</option>
    @endforeach
  </select>
</div>

<button type="submit" class="btn btn-primary" onclick="return post_rejudge();"> {{trans('wzoj.submit')}} </button>

<p class="text-muted">{{trans('wzoj.msg_problem_rejudge_helper')}}</p>

<p><span id="rejudge_complete">0</span>/<span id="rejudge_total">0</span></p>

</form>
@endsection

@section ('scripts')
<script>
function post_rejudge(){
	$.get('/admin/problem-rejudge/check', $('#problem_rejudge_form').serialize()).done(async function( data ){
		var confirm_msg = TRANS['cnt_solutions'] + ": " + data.count + "\n" +
				  TRANS['estimate_time'] + ": " + ms2text(data.time_used) + "\n" +
				  TRANS['confirm_rejudge'] + "?\n" +
				  TRANS['rejudge_remind'];
		if(confirm(confirm_msg)){
			$('#rejudge_total').html(data.sids.length);
			for(var i=0; i < data.sids.length; i++){
				sid = data.sids[i];

				$.post('/admin/problem-rejudge', {
					_token: "{{csrf_token()}}",
					solution_id: sid,
				});

				$('#rejudge_complete').html(i+1);
				await delay(1000);
			}
			alert(TRANS['rejudge_complete']);
		}
	});
	return false;
}
</script>
@endsection
