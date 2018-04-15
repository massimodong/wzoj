@extends ('layouts.master')

@section ('title')
{{trans('wzoj.homework').'-'.$group->name}}
@endsection

@section ('content')

<div class="col-xs-12">
  <table id="homework-status-table" class="table table-striped">
    <thead>
      <tr>
        <th style="width: 8%">{{trans('wzoj.username')}}</th>
        <th style="width: 8%">{{trans('wzoj.fullname')}}</th>
        <th style="width: 9%">x/{{$total_score}}</th>
	@foreach ($problem_cols as $psid=>$problems)
	  @foreach ($problems as $problem)
	    <th><a href="/s/{{$psid}}/{{$problem->id}}" style="color:black">{{$problem->name}}</a></th>
	  @endforeach
	@endforeach
      </tr>
    </thead>
    <tbody>
    @foreach ($group->users as $user)
      <tr @if (Auth::check() && ($user->id == Auth::user()->id)) class="info" id="my-row" @endif>
        <td><a href="/users/{{$user->id}}">{{$user->name}}</a></td>
	<td>{{$user->fullname}}</td>
	<td>{{$user_total_scores[$user->id]}}</td>
	@foreach ($problem_cols as $psid=>$problems)
	  @foreach ($problems as $problem)
	    <td>
	    @if (($score = ($user_max_scores[$user->id][$psid][$problem->id])) >= 100)
	      <a href="/solutions?problemset_id={{$psid}}&user_name={{$user->name}}&problem_id={{$problem->id}}"><span style="color: green;">{{$score}}</span></a>
	    @elseif ($score >= 0)
	      <a href="/solutions?problemset_id={{$psid}}&user_name={{$user->name}}&problem_id={{$problem->id}}"><span style="color: red;">{{$score}}</span></a>
	    @else
	      <span style="color: grey">0</span>
	    @endif
	    </td>
	  @endforeach
	@endforeach
      </tr>
    @endforeach
    </tbody>
  </table>
</div>
@endsection

@section ('scripts')
<script>
$('#homework-status-table').DataTable({
	searching: false,
	paging: false,
	bInfo: false,
	order: [[2, "desc"]],
});
window.location.hash='my-row';
</script>
@endsection
