@extends ('layouts.master')

@section ('title')
{{trans('wzoj.contests')}} @if (isset($tag))- {{$tag}}@endif
@endsection

@section ('head')
@parent
<link rel="stylesheet" href={{ojcache("/include/css/datatables.min.css")}}>
@endsection

@section ('content')
{!! Breadcrumbs::render('contests') !!}

@can ('create',App\Problemset::class)
<form method='POST' action="/s">
    {{csrf_field()}}
    <input name="type" value="apio" style="display:none">
    <button type="submit" class="btn btn-default">+</button>
</form>
@endcan

<div class="table-responsive">
  <table id="contests-table" class="table">
  <thead>
    <tr>
      <th>{{trans('wzoj.id')}}</th>
      <th>{{trans('wzoj.name')}}</th>
      <th>{{trans('wzoj.type')}}</th>
      <th>{{trans('wzoj.contest_start_at')}}</th>
      <th>{{trans('wzoj.contest_end_at')}}</th>
      <th>{{trans('wzoj.tag')}}</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($problemsets as $problemset)
    <tr>
      <td>{{$problemset->id}}</td>
      <td>
        <a href='/s/{{$problemset->id}}'> {{$problemset->name}} </a>
        @can ('update',$problemset)
        <a href='/s/{{$problemset->id}}/edit'> [{{trans('wzoj.edit')}}] </a>
        @endcan
        @if (strtotime($problemset->contest_start_at)<time())
        <a href='/s/{{$problemset->id}}/ranklist'>[{{trans('wzoj.ranklist')}}]</a>
        @endif
      </td>
      <td>{{trans('wzoj.problem_type_'.$problemset->type)}}</td>
      <td><span style="color: green">{{$problemset->contest_start_at}}</span></td>
      <td><span style="color: red">{{$problemset->contest_end_at}}</span></td>
      <td>{{$problemset->tag}}</td>
    </tr>
    @endforeach
  </tbody>
  </table>
</div>

@endsection

@section ('scripts')
<script src={{ojcache("/include/js/datatables.min.js")}}></script>
<script>
$('#contests-table').DataTable({
	searching: true,
	paging: true,
	order: [[3, "desc"], [0, "desc"]],
});
</script>
@endsection
