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

<div class="table-responsive">
<table class="table ranklist-table">
  <thead>
    <tr>
      <th> {{trans('wzoj.rank')}} </th>
      <th> {{trans('wzoj.user')}} </th>
      <th><a href="/solutions?problemset_id={{$problemset->id}}"> {{trans('wzoj.score')}} </a></th>
      @foreach ($problems as $problem)
        <th><a href="/s/{{$problemset->id}}/{{$problem->id}}"> {{$problem->name}} </a></th>
      @endforeach
    </tr>
  </thead>
  <tbody>
    @foreach ($table as $key => $record)
      <tr>
        @if ($record->score >= 0)
        <td> {{$record->rank + 1}} </td>
        <td><a href="/users/{{$record->user->id}}"> {{$record->user->fullname}} </a></td>
        <td><a href="/solutions?problemset_id={{$problemset->id}}&user_name={{$record->user->name}}"><b> {{$record->score}} </b></a></td>
          @foreach ($problems as $problem)
          <td><a href="/solutions?problemset_id={{$problemset->id}}&user_name={{$record->user->name}}&problem_id={{$problem->id}}">
            {{$record->problem_scores[$problem->id]}}
            @if (!$contest_running && $record->problem_corrected_scores[$problem->id] > $record->problem_scores[$problem->id])
              ({{$record->problem_corrected_scores[$problem->id]}})
            @endif
          </a></td>
          @endforeach
        @else
        <td> - </td>
        <td><a href="/users/{{$record->user->id}}"> {{$record->user->fullname}} </a></td>
        <td> - </td>
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
        @endif
      </tr>
    @endforeach
  <tbody>
</table>
</div>

@endsection
