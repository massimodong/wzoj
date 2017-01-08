<table class='table'>
  <tr>
    <th style='width:5%'>{{trans('wzoj.user')}}</th>
    <th style='width:5%'>{{trans('wzoj.class')}}</th>
    <th style='width:5%'>{{trans('wzoj.score')}}</th>
    @foreach ($problemset->problems as $problem)
	<th><a href='/s/{{$problemset->id}}/{{$problem->id}}'>{{$problem->name}}</a></th>
    @endforeach
  </tr>
</table>

<table id="rank-table">
</table>

<script>
//define template
var user_template = "<tr>" +
			"<td class='rank-user' style='width:5%'></td>" +
			"<td class='rank-class' style='width:5%'></td>" +
			"<td class='rank-score' style='width:5%'></td>";
			@foreach ($problemset->problems as $problem)
			user_template += "<td class='problem-{{$problem->id}}'></td>"
			@endforeach
user_template += '</tr>';
</script>
