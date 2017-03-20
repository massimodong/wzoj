@extends ('layouts.master')

@section ('title')
{{$problemset->name}}
@endsection

@include ('layouts.contest_header')

@section ('content')

<h1 class='page-header text-center'>{{$problemset->name}}</h1>
@if (Auth::check() && Auth::user()->has_role('admin'))
<div class="pull-right"><a href="/s/{{$problemset->id}}/edit">{{trans('wzoj.edit')}}</a></div>
@endif

<div id="home" class="tab-pane fade in active">
  {!! Purifier::clean($problemset->description) !!}
  <center><p>
  <span style="color:#00CC00">{{trans('wzoj.contest_start_at')}}:{{$problemset->contest_start_at}}</span><br>
  <span style="color:#FF0000">{{trans('wzoj.contest_end_at')}}:{{$problemset->contest_end_at}}</span>
  </p></center>

  <table class="table table-striped">
  <thead>
    <tr>
      <th style='width:5%'></th>
      <th style='width:5%'>{{trans('wzoj.index')}}</th>
      <th>{{trans('wzoj.name')}}</th>
      <th style='width:13%'>{{trans('wzoj.source')}}</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($problems as $problem)
    <tr>
      <td>
        @if ($problem->maxscore >= 100)
          <span class="glyphicon glyphicon-ok" style="color:green"></span>
        @elseif (isset($problem->maxscore))
          <span style="color:red">
          {{$problem->maxscore}}</span>
        @endif
      </td>
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
