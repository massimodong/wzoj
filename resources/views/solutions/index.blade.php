@extends ('layouts.master')

@section ('title')
{{trans('wzoj.solutions')}}
@endsection

@if (isset($problemset) && $problemset->type <> 'set')
    @include ('layouts.contest_header')
@endif

@section ('content')
<div class='col-xs-12'>

<center><form class="form-inline" method="GET">
  <div class="form-group">
    <label for="problemset_id">{{trans('wzoj.problemset')}}</label>
    <input type="text" class="form-control" id="problemset_id" name="problemset_id" size="1" value="{{$request->problemset_id}}">
  </div>
  <div class="form-group">
    <label for="user_name">{{trans('wzoj.user')}}</label>
    <input type="text" class="form-control" id="user_name" name="user_name" size="5" value="{{$request->user_name}}">
  </div>
  <div class="form-group">
    <label for="problem_id">{{trans('wzoj.problem')}}</label>
    <input type="text" class="form-control" id="problem_id" name="problem_id" size="1" value="{{$request->problem_id}}">
  </div>
  <div class="form-group">
    <label for="score_min">{{trans('wzoj.score')}}</label>
    <input type="text" class="form-control" id="score_min" name="score_min" size="1" value="{{$request->score_min}}">-
    <input type="text" class="form-control" id="score_max" name="score_max" size="1" value="{{$request->score_max}}">
  </div>
  <div class="form-group">
    <label for="language">{{trans('wzoj.language')}}</label>
    <!--<input type="text" class="form-control" id="language" name="language" size="1" value="{{$request->language}}">-->
    <select class="form-control" id="language" name="language">
      <option value=""></option>
      <option value="0" {{$request->language=='0'?"selected":""}}>C</option>
      <option value="1" {{$request->language=='1'?"selected":""}}>C++</option>
      <option value="2" {{$request->language=='2'?"selected":""}}>Pascal</option>
      <option value="4" {{$request->language=='4'?"selected":""}}>Python</option>
    </select>
  </div>
  <div class="form-group">
    <label for="status">{{trans('wzoj.status')}}</label>
    <select class="form-control" id="status" name="status">
      <option value=""></option>
      @for ($i=0;$i<=4;++$i)
        <option value="{{$i}}" {{$request->status==strval($i)?"selected":""}}>{{trans('wzoj.solution_status_'.$i)}}</option>
      @endfor
    </select>
  </div>
  <button type="submit" class="btn btn-default">{{trans('wzoj.search')}}</button>
</form></center>

<div class='pull-right small'>
  <ul class="pager">
    @if ($url_limits <> '')
      <li><a href='/solutions?{{$url_limits}}'>{{trans('wzoj.toppage')}}</a></li>
    @else
      <li><a href='/solutions'>{{trans('wzoj.toppage')}}</a></li>
    @endif

    @if ($prev_url <> '')
      <li><a href='{{$prev_url.$url_limits}}'>{{trans('wzoj.prevpage')}}</a></li>
    @endif

    @if ($next_url <> '')
      <li><a href='{{$next_url.$url_limits}}'>{{trans('wzoj.nextpage')}}</a></li>
    @endif
  </ul>
</div>

<table class="table table-striped">
<thead>
    <tr>
        <th style='width:7%'>{{trans('wzoj.id')}}</th>
        <th style='width:10%'>{{trans('wzoj.user')}}</th>
        <th style='width:18%'>{{trans('wzoj.problem')}}</th>
        <th style='width:9%'>{{trans('wzoj.score')}}</th>
        <th style='width:8%'>{{trans('wzoj.time_used')}}</th>
        <th style='width:11%'>{{trans('wzoj.memory_used')}}</th>
        <th style='width:8%'>{{trans('wzoj.language')}}</th>
        <th style='width:8%'>{{trans('wzoj.code_length')}}</th>
        <th style='width:8%'>{{trans('wzoj.judger')}}</th>
        <th style='width:13%'>{{trans('wzoj.submitted_at')}}</th>
    </tr>
</thead>
<tbody id='solutions-tbody'>
@foreach ($solutions as $solution)
    <tr id= 'tr-{{$solution->id}}' class='clickable-row' data-href='/solutions/{{$solution->id}}'>
        <td>{{$solution->id}}</td>
	<td>{{$solution->user?$solution->user->name:""}}</td>
	<td>{{$solution->problem?$solution->problem->name:""}}</td>

	<td class='solution-score'>
	@if ($solution->status == SL_JUDGED)
	  @if ($solution->ce)
	    {{trans('wzoj.compile_error')}}
	  @else
	    {{$solution->score}}
	    @if (isset($solution->sim) && $solution->shouldShowSim())
	      <span style="color:yellow" title="{{trans('wzoj.sim_warning', ['sid' => $solution->sim->solution2_id, 'rate' => $solution->sim->rate])}}" class="glyphicon glyphicon-warning-sign"></span>
	    @endif
	  @endif
	@else
	    <div id='solution-{{$solution->id}}' @if($solution->status==3)class="running-solution"@endif data-testcases='{{json_encode($solution->testcases)}}' data-cnttestcases="{{$solution->cnt_testcases}}">
              {{trans('wzoj.solution_status_'.$solution->status)}}
	    </div>
	@endif
	</td>

	<td class='solution-timeused'>{{$solution->time_used}}ms</td>
	<td class='solution-memoryused'>{{sprintf('%.2f', $solution->memory_used / 1024 / 1024)}}MB</td>
	<td>{{trans('wzoj.programing_language_'.$solution->language)}}</td>
	<td>{{$solution->code_length}}B</td>
	<td class='solution-judger'>{{$solution->judger?$solution->judger->name:""}}</td>
	<td class='solution-submitted_at'>{{$solution->created_at}}</td>
    </tr>
@endforeach
</tbody>
</table>

<div class='pull-right small'>
  <ul class="pager">
    @if ($url_limits <> '')
      <li><a href='/solutions?{{$url_limits}}'>{{trans('wzoj.toppage')}}</a></li>
    @else
      <li><a href='/solutions'>{{trans('wzoj.toppage')}}</a></li>
    @endif

    @if ($prev_url <> '')
      <li><a href='{{$prev_url.$url_limits}}'>{{trans('wzoj.prevpage')}}</a></li>
    @endif

    @if ($next_url <> '')
      <li><a href='{{$next_url.$url_limits}}'>{{trans('wzoj.nextpage')}}</a></li>
    @endif
  </ul>
</div>

</div>
@endsection

@section ('scripts')
<script>
jQuery(document).ready(function($) {
	$(".clickable-row").click(function() {
		window.document.location = $(this).data("href");
	});
	$('.running-solution').each(function(index){
		solutions_update_progress($(this));
	});
	solutions_progress();
	solutions_new_update();
});
</script>
@endsection
