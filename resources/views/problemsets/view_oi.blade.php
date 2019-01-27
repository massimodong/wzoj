@extends ('layouts.master')

@section ('title')
{{$problemset->name}}
@endsection

@include ('layouts.contest_header')

@section ('content')

<h1 class='page-header text-center'>{{$problemset->name}}</h1>
@can ('update', $problemset)
<div class="pull-right"><a href="/s/{{$problemset->id}}/edit">{{trans('wzoj.edit')}}</a></div>
@endif

<div id="home" class="tab-pane fade in active">
  {!! $problemset->description !!}
  <p>
  <span style="color:#00CC00">{{trans('wzoj.contest_start_at')}}:{{$problemset->contest_start_at}}</span><br>
  <span style="color:#FF0000">{{trans('wzoj.contest_end_at')}}:{{$problemset->contest_end_at}}</span>
  </p>

  <table class="table table-striped">
  <thead>
    <tr>
      <th style='width:5%'></th>
      <th style='width:5%'>{{trans('wzoj.index')}}</th>
      <th class="text-left">{{trans('wzoj.name')}}</th>
      <th style='width:13%'>{{trans('wzoj.source')}}</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($problems as $problem)
    <tr>
      <td>
        @if (isset($max_scores[$problem->id]) && $max_scores[$problem->id] >= 100)
          <span class="glyphicon glyphicon-ok" style="color:green"></span>
        @elseif (isset($max_scores[$problem->id]) && $max_scores[$problem->id] >= 0)
          <span style="color:red">
          {{$max_scores[$problem->id]}}</span>
        @endif
      </td>
      <td>{{$problem->pivot->index}}</td>
      <td class="text-left"><a href='/s/{{$problemset->id}}/{{$problem->id}}'>{{$problem->name}}</a></td>
      <td>{{$problem->source}}</td>
    </tr>
    @endforeach
  </tbody>
  </table>
</div>
<!-- home -->

@endsection
