@extends ('layouts.master')

@section ('title')
{{$problemset->name}}
@endsection

@section ('content')
{!! Breadcrumbs::render('problemset', $problemset) !!}
<h1 class='page-header text-center'>{{$problemset->name}}</h1>
<h6 class='text-center'>
  <span style="color:green">{{$virtual_participation ? $virtual_participation->contest_start_at : $problemset->contest_start_at}}</span>
  <span style="color:red">{{$virtual_participation ? $virtual_participation->contest_end_at : $problemset->contest_end_at}}</span>
  @if (!$problemset->isHideSolutions()) <a href="/s/{{$problemset->id}}/ranklist">{{trans('wzoj.ranklist')}}</a> @endif
</h6>
@can ('update', $problemset)
<div class="pull-right"><a href="/s/{{$problemset->id}}/edit">{{trans('wzoj.edit')}}</a></div>
@endif

{!! $problemset->description !!}

<div class="table-responsive">
  <table class="table">
    <thead>
      <tr>
        <th></th>
        <th>{{trans('wzoj.index')}}</th>
        <th>{{trans('wzoj.name')}}</th>
        <th>{{trans('wzoj.source')}}</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($problems as $problem)
      <tr>
        <td>
        @if (isset($max_scores[$problem->id]) && $max_scores[$problem->id] >= 100)
          <span class="fa fa-check" style="color:green"></span>
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
  @if ($problemset->participate_type == 1)
    @if ($virtual_participation)
    @else
      {{trans('wzoj.contest_duration')}}: {{time2string($problemset->contest_duration)}}
      <form method="POST" action="/s/{{$problemset->id}}/virtual_participate">
        {{csrf_field()}}
        <button type="submit" class="btn btn-primary">{{trans('wzoj.participate_contest')}}</button>
      </form>
    @endif
  @endif
</div>

@endsection
