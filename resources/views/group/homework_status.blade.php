@extends ('layouts.master')

@section ('title')
{{trans('wzoj.homework').'-'.$group->name}}
@endsection

@section ('head')
@parent
<link rel="stylesheet" href={{ojcache("/include/css/datatables.min.css")}}>
@endsection

@section ('content')

<div class="table-responsive">
<table id="homework-status-table" class="table ranklist-table">
  <thead>
    <tr>
      <th style="display: none"></th>
      <th>{{trans('wzoj.user')}}</th>
      <th>x/{{$total_score}}</th>
      @foreach ($problems as $problem)
        <th><a href="/s/{{$problem->pivot->problemset_id}}/{{$problem->id}}" title="{{$problem->name}}" style="color:black; max-width: 3em; overflow: hidden; display: block">{{$problem->name}}</a></th>
      @endforeach
    </tr>
  </thead>
  <tbody>
  @foreach ($group->users as $user)
    <tr @if (Auth::check() && ($user->id == Auth::user()->id)) class="table-primary" @endif>
      <td style="display: none">@if (Auth::check() && ($user->id == Auth::user()->id)) 1 @endif</td>
      <td>@include ('partials.user_badge', ['user' => $user])</td>
      <td>{{$user_total_scores[$user->id]}}</td>
      @foreach ($problems as $problem)
        <td>
          @if (($score = ($user_max_scores[$user->id][$problem->pivot->problemset_id][$problem->id])) >= 100)
            <a href="/solutions?problemset_id={{$problem->pivot->problemset_id}}&user_name={{$user->name}}&problem_id={{$problem->id}}"><span style="color: green;">{{$score}}</span></a>
          @elseif ($score >= 0)
            <a href="/solutions?problemset_id={{$problem->pivot->problemset_id}}&user_name={{$user->name}}&problem_id={{$problem->id}}"><span style="color: red;">{{$score}}</span></a>
          @else
            <span style="color: grey">0</span>
          @endif
        </td>
      @endforeach
    </tr>
  @endforeach
  </tbody>
</table>
</div>
@endsection

@section ('scripts')
<script src={{ojcache("/include/js/datatables.min.js")}}></script>
<script>
$('#homework-status-table').DataTable({
	searching: false,
	paging: false,
	bInfo: false,
	order: [[0, "desc"], [2, "desc"]],
});
</script>
@endsection
