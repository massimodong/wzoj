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
      @foreach ($problem->tags as $tag)
	<span class="label label-default">{{$tag->name}}</span>
      @endforeach
    </li>
  @endforeach
</ul>
@endsection
