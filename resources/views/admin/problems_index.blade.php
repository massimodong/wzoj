@extends ('admin.layout')

@section ('title')
{{trans('wzoj.problems')}}
@endsection

@section ('content')

<div class="col-xs-12 row">

@can ('create',App\Problem::class)
<div class="col-xs-12">
    <form action='/admin/problems' method='POST'>
    {{csrf_field()}}
    <button type="submit" class="btn btn-default">+</button>
    </form>
</div>
@endcan

<div class="col-xs-12" style="height:10px"></div>

<table id="problems_table" class="table">
  <thead>
    <tr>
      <th style="width: 5%">{{trans('wzoj.id')}}</th>
      <th>{{trans('wzoj.name')}}</th>
      <th style="width: 10%">{{trans('wzoj.type')}}</th>
      <th style="width: 5%">spj</th>
      <th style="width: 10%">{{trans('wzoj.source')}}</th>
      <th style="width: 10%">{{trans('wzoj.tags')}}</th>
      <th style="width: 10%">{{trans('wzoj.pass_rate')}}</th>
      <th style="width: 15%">{{trans('wzoj.problemsets')}}</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($problems as $problem)
    <tr>
      <td>{{$problem->id}}</td>
      <td><a href="/admin/problems/{{$problem->id}}">{{$problem->name}}</a></td>
      <td>{{trans('wzoj.problem_type_'.$problem->type)}}</td>
      <td>{{$problem->spj?"spj":""}}</td>
      <td>{{$problem->source}}</td>
      <td>
        <span>
	        @include ('partials.problem_tags')
        </span>
      </td>
      <td>{{$problem->cnt_ac}}/{{$problem->cnt_submit}}</td>
      <td>
        @foreach ($problem->problemsets as $problemset)
          {{$problemset->name}}
        @endforeach
      </td>
    </tr>
    @endforeach
  </tbody>
</table>

<form id="problems_form" action="/admin/problems" method="POST">
{{csrf_field()}}
</form>

</div>
@endsection

@section ('scripts')
<script>
$('#problems_table').DataTable({
	searching: true,
	paging: true,
	order: [[0, "asc"]],
  iDisplayLength: 100,
});
</script>
@endsection
