@extends ('layouts.master')

@section ('title')
{{trans('wzoj.ranklist')}}
@endsection

@section ('content')
{!! Breadcrumbs::render('contest_ranklist', $problemset) !!}
@if (Auth::check() && Auth::user()->has_role('admin'))
<div>
<a href="./ranklist_csv" class="pull-right">{{trans('wzoj.download')}}</a>
</div>
@endif

<div id="ranklist-table-container" class="table-responsive">
<table class="table ranklist-table">
  <thead>
    <tr>
      <th style="display: none"></th>
      <th> {{trans('wzoj.user')}} </th>
      @foreach ($problems as $problem)
        <th><a href="/s/{{$problemset->id}}/{{$problem->id}}" title="{{$problem->name}}"> {{$problem->pivot->index}} </a></th>
      @endforeach
      <th><a href="/solutions?problemset_id={{$problemset->id}}"> {{trans('wzoj.total_score')}} </a></th>
      <th>{{trans('wzoj.rank')}}</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($table as $key => $record)
      <tr>
        @if ($record->score >= 0)
        <td style="display: none"></td>
        <td>@include ('partials.user_badge', ['user' => $record->user])</td>
          @foreach ($problems as $problem)
          <td><a href="/solutions?problemset_id={{$problemset->id}}&user_name={{$record->user->name}}&problem_id={{$problem->id}}">
            {{$record->problem_scores[$problem->id]}}
            @if (!$contest_running && $record->problem_corrected_scores[$problem->id] > $record->problem_scores[$problem->id])
              ({{$record->problem_corrected_scores[$problem->id]}})
            @endif
          </a></td>
          @endforeach
        <td><a href="/solutions?problemset_id={{$problemset->id}}&user_name={{$record->user->name}}"><b> {{$record->score}} </b></a></td>
        <td>{{$record->rank+1}}</td>
        @else
        <td style="display: none"></td>
        <td>@include ('partials.user_badge', ['user' => $record->user])</td>
          @foreach ($problems as $problem)
          <td>
            @if (!$contest_running && $record->problem_corrected_scores[$problem->id] >= 0)
            <a href="/solutions?problemset_id={{$problemset->id}}&user_name={{$record->user->name}}&problem_id={{$problem->id}}">
              ({{$record->problem_corrected_scores[$problem->id]}})
            </a>
            @else
              -
            @endif
          </td>
          @endforeach
        <td>-</td>
        <td>-</td>
        @endif
      </tr>
    @endforeach
  <tbody>
</table>
</div>

@endsection

@section ('scripts')
<script>
  if(window.chrome){ // Restore scroll position for chrome. Firefox does it automatically.
    var lastPos = localStorage.getItem(location.href + "scroll");
    if(lastPos) $('#ranklist-table-container').scrollLeft(lastPos);
    else $('#ranklist-table-container').scrollLeft($('#ranklist-table-container').width());
    window.addEventListener("beforeunload", () => {
        localStorage.setItem(location.href + "scroll", $('#ranklist-table-container').scrollLeft());
    });
  }
</script>
@endsection
