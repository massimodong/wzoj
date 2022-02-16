@extends ('layouts.master')

@section ('title')
{{trans('wzoj.solution')}}
@endsection

@section ('content')
{!! Breadcrumbs::render('solution', $solution) !!}
<div class="table-responsive">
<table class="table">
  <tbody>
    <tr>
      <th>{{trans('wzoj.id')}}</th>
      <td>{{$solution->id}}</td>

      <th>{{trans('wzoj.time_used')}}</th>
      <td>{{$solution->time_used}}ms</td>
    </tr>

    <tr>
      <th>{{trans('wzoj.user')}}</th>
      <td>@include ('partials.user_badge', ['user' => $solution->user])</td>

      <th>{{trans('wzoj.memory_used')}}</th>
      <td>{{sprintf('%.2f', $solution->memory_used / 1024 / 1024)}}MB</td>
    </tr>

    <tr>
      <th>{{trans('wzoj.problem')}}</th>
      @if ($solution->problemset_id > 0)
        @if ($problemset->public || Gate::allows('view', $problemset))
          <td><a href='/s/{{$solution->problemset->id}}/{{$solution->problem->id}}'>{{$solution->problem->name}}</a></td>
        @else
          <td>{{$solution->problem->name}}</td>
        @endif
      @else
        @if (Auth::check() && Auth::user()->has_role('admin'))
          <td><a href='/admin/problems/{{$solution->problem->id}}'>{{$solution->problem->name}}</a></td>
        @else
          <td>{{$solution->problem->name}}</td>
        @endif
      @endif

      <th>{{trans('wzoj.score')}}</th>
      <td>{{$solution->score}}</td>
    </tr>

    <tr>
      <th>{{trans('wzoj.language')}}</th>
      <td>{{trans('wzoj.programing_language_'.$solution->language)}}</td>

      <th>{{trans('wzoj.status')}}</th>
      <td>{{trans('wzoj.solution_status_'.$solution->status)}}</td>
    </tr>

    <tr>
      <th>{{trans('wzoj.code_length')}}</th>
      <td>{{$solution->code_length}}B</td>

      <th>{{trans('wzoj.judger')}}</th>
      <td>{{$solution->judger?$solution->judger->name:""}}</td>
    </tr>

    <tr>
      <th>{{trans('wzoj.submitted_at')}}</th>
      <td>{{$solution->created_at}}</td>

      <th>{{trans('wzoj.judged_at')}}</th>
      <td>{{$solution->judged_at}}</td>
    </tr>
  </tbody>
</table>
</div>

@if ($solution->status == SL_JUDGED)
  @if (isset($solution->ce))
    <h3>{{trans('wzoj.compile_error')}}</h3>
    @can ('view_code', $solution)
      <pre>{{$solution->ce}}</pre>
    @endcan
  @else
    <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <th>{{trans('wzoj.testcase')}}</th>
          <th>{{trans('wzoj.score')}}</th>
          @if ($solution->problem->type != 3)
          <th>{{trans('wzoj.time_used')}}</th>
          <th>{{trans('wzoj.memory_used')}}</th>
          @endif
          <th>{{trans('wzoj.verdict')}}</th>
          <th>{{trans('wzoj.checklog')}}</th>
        </tr>
      </thead>
      <tbody>
        @if ($solution->problem->use_subtasks && is_array($solution->problem->subtasks))
          @foreach ($solution->problem->subtasks as $subtask)
            <tr>
              <td colspan="6">{{trans('wzoj.subtask')}} ({{$subtask->score}} pts)</td>
            </tr>
            @foreach ($subtask->testcases as $name)
              @if (isset($testcases[$name]) && ($testcase = $testcases[$name]))
                @include ('partials.showsolution')
              @else
                <tr>
                  <td>{{$name}}</td>
                  <td colspan="5"> -- no data -- </td>
                </tr>
              @endif
            @endforeach
          @endforeach
        @else
          @foreach ($testcases as $testcase)
            @include ('partials.showsolution')
          @endforeach
        @endif
      </tbody>
    </table>
    </div>
  @endif
@endif

@if ($solution->problem->type <> 3)
  @can ('view_code', $solution)
    <pre><code class="{{ojPrimusClass($solution->language)}}">{{$solution->code}}</code></pre>
  @endcan
@endif

@endsection
