@extends ('layouts.master')

@section ('title')
{{trans('wzoj.search_problem')}}
@endsection

@section ('content')
<div class="table-responsive">
 <table class="table table-striped">
  <thead>
    <tr>
      <th class='text-left'>{{trans('wzoj.problemset')}}</th>
      <th class="text-left">{{trans('wzoj.name')}}</th>
      <th>{{trans('wzoj.source')}}</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($result as $item)
    <tr>
      <td class="text-left">{{$problemsets[$item->problemset_id]->name}}</td>
      <td class="text-left"><a href='/s/{{$item->problemset_id}}/{{$item->id}}'>{{$problems[$item->id]->name}}</a>
        <span class="pull-right">
	        @include ('partials.problem_tags', ['problem' => $problems[$item->id]])
        </span>
      </td>
      <td>{{$problems[$item->id]->source}}</td>
    </tr>
    @endforeach
  </tbody>
  </table>
</div>
@endsection
