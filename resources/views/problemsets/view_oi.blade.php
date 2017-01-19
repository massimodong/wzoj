@extends ('layouts.master')

@section ('title')
{{$problemset->name}}
@endsection

@include ('layouts.contest_header')

@section ('content')

<h1 class='page-header text-center'>{{$problemset->name}}</h1>

<div id="home" class="tab-pane fade in active">
  {!! Purifier::clean($problemset->description) !!}

  <table class="table table-striped">
  <thead>
    <tr>
      <th style='width:5%'>{{trans('wzoj.index')}}</th>
      <th>{{trans('wzoj.name')}}</th>
      <th style='width:13%'>{{trans('wzoj.source')}}</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($problems as $problem)
    <tr>
      <td>{{$problem->pivot->index}}</td>
      <td><a href='/s/{{$problemset->id}}/{{$problem->id}}'>{{$problem->name}}</a></td>
      <td>{{$problem->source}}</td>
    </tr>
    @endforeach
  </tbody>
  </table>
</div>
<!-- home -->

@endsection
