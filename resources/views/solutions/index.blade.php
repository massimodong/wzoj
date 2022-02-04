@extends ('layouts.master')

@section ('title')
{{trans('wzoj.solutions')}}
@endsection

@section ('content')
{!! Breadcrumbs::render('solutions') !!}
<form id="solutions-search" class="form-inline" method="GET">
  <label class="sr-only" for="problemset_id">{{trans('wzoj.problemset')}}</label>
  <input type="text" class="form-control mb-2 mr-2" id="problemset_id" name="problemset_id" size="4" value="{{$request->problemset_id}}" placeholder="{{trans('wzoj.problemset')}}">

  <label class="sr-only" for="user_name">{{trans('wzoj.user')}}</label>
  <input type="text" class="form-control mb-2 mr-2" id="user_name" name="user_name" value="{{$request->user_name}}" placeholder="{{trans('wzoj.user')}}">

  <label class="sr-only" for="problem_id">{{trans('wzoj.problem')}}</label>
  <input type="text" class="form-control mb-2 mr-2" id="problem_id" name="problem_id" size="4" value="{{$request->problem_id}}" placeholder="{{trans('wzoj.problem')}}">

  <div class="form-group">
    <label class="sr-only" for="score_min">score-min</label>
    <input type="text" class="form-control mb-2 mr-2" id="score_min" name="score_min" size="1" value="{{$request->score_min}}" placeholder="0">-

    <label class="sr-only" for="score_max">score-max</label>
    <input type="text" class="form-control mb-2 mr-2" id="score_max" name="score_max" size="1" value="{{$request->score_max}}" placeholder="100">
  </div>

  <label class="sr-only" for="language">{{trans('wzoj.language')}}</label>
  <!--<input type="text" class="form-control" id="language" name="language" size="1" value="{{$request->language}}">-->
  <select class="custom-select mb-2 mr-2" id="language" name="language">
    <option value="">{{trans('wzoj.language')}}</option>
    <option value="0" {{$request->language=='0'?"selected":""}}>C</option>
    <option value="1" {{$request->language=='1'?"selected":""}}>C++</option>
    <option value="2" {{$request->language=='2'?"selected":""}}>Pascal</option>
    <option value="4" {{$request->language=='4'?"selected":""}}>Python</option>
  </select>

  <label class="sr-only" for="status">{{trans('wzoj.status')}}</label>
  <select class="custom-select mb-2 mr-2" id="status" name="status">
    <option value="">{{trans('wzoj.status')}}</option>
    @for ($i=0;$i<=4;++$i)
      <option value="{{$i}}" {{$request->status==strval($i)?"selected":""}}>{{trans('wzoj.solution_status_'.$i)}}</option>
    @endfor
  </select>

  <button type="submit" class="btn btn-primary mb-2">{{trans('wzoj.search')}}</button>
</form>

{{$solutions->links()}}

<div class="table-responsive">
<table class="table">
  <thead>
    <tr>
      <th>{{trans('wzoj.id')}}</th>
      <th>{{trans('wzoj.user')}}</th>
      <th>{{trans('wzoj.problem')}}</th>
      <th>{{trans('wzoj.score')}}</th>
      <th>{{trans('wzoj.time_used')}}</th>
      <th>{{trans('wzoj.memory_used')}}</th>
      <th>{{trans('wzoj.language')}}</th>
      <th>{{trans('wzoj.code_length')}}</th>
      <th>{{trans('wzoj.submitted_at')}}</th>
    </tr>
  </thead>
  <tbody id='solutions-tbody'>
    @foreach ($solutions as $solution)
    <tr>
      <td><a href="/solutions/{{$solution->id}}">{{$solution->id}}</a></td>
      <td>@if ($solution->user)@include ('partials.user_badge', ['user' => $solution->user])@endif </td>
      <td>{{$solution->problem?$solution->problem->name:""}}</td>

      <td>
      @if ($solution->status == SL_JUDGED)
        @if ($solution->ce)
	        {{trans('wzoj.compile_error')}}
	      @else
          {{$solution->score}}
        @endif
      @else
        {{trans('wzoj.solution_status_0')}}
      @endif
      </td>

      <td>{{$solution->time_used}}ms</td>
      <td>{{sprintf('%.2f', $solution->memory_used / 1024 / 1024)}}MB</td>
      <td>{{trans('wzoj.programing_language_'.$solution->language)}}</td>
      <td>{{$solution->code_length}}B</td>
      <td>{{$solution->created_at}}</td>
    </tr>
    @endforeach
  </tbody>
</table>
</div>

{{$solutions->links()}}

@endsection
