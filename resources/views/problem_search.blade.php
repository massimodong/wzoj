@extends ('layouts.master')

@section ('title')
{{trans('wzoj.search_problem')}}
@endsection

@section ('content')
<ul class="list-group">
  @foreach ($problems as $problem)
    <li class="list-group-item">
      <a href="/s/{{$problem->pivot->problemset_id}}">{{$problemsets[$problem->pivot->problemset_id]->name}}</a> /
      <a href="/s/{{$problem->pivot->problemset_id}}/{{$problem->id}}">{{$problem->name}}</a>
      @include ('layouts.problem_tags', ['problem' => $problem])
    </li>
  @endforeach
</ul>
@endsection
