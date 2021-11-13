@extends ('layouts.master')

@section ('title')
{{$problemset->name}}
@endsection

@section ('content')
{!! Breadcrumbs::render('problemset', $problemset) !!}
<h1 class='page-header text-center'>{{$problemset->name}}</h1>
<h6 class='text-center'>
  <span style="color:green">{{$problemset->contest_start_at}}</span> -
  <span style="color:red">{{$problemset->contest_end_at}}</span>
  @if (!$problemset->isHideSolutions()) <a href="/s/{{$problemset->id}}/ranklist">{{trans('wzoj.ranklist')}}</a> @endif
</h6>
<div class="card mb-1">
  <div class="card-body text-center">
    @if ($virtual_participation)
    <div class="float-left">
      <span style="color: green">{{trans('wzoj.start_contest')}}：{{$virtual_participation->contest_start_at}}</span><br>
      <span style="color: red">{{trans('wzoj.end_contest')}}：{{$virtual_participation->contest_end_at}}</span>
    </div>
    @endif

    <span style="font-size: xx-large" class="align-middle">
      {{trans('wzoj.contest_period_'.$contest_period)}}
    </span>
    @if ($contest_period != CONTEST_ENDED)
      <span style="font-size: xx-large" class="align-middle">
        {{trans('wzoj.remaining')}}:
      </span>
      <div style="display: inline-block;" class="align-middle">
        <pre id="h-countdown" style="margin-bottom: 0; overflow: visible; font-size: xx-large"></pre>
      </div>
    @else
    @endif
  </div>
</div>
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

@section ('scripts')
<script>
  @if ($contest_period == CONTEST_PENDING)
    var countdown = {{$contest_start_time - time()}};
    do_countdown();
  @elseif ($contest_period == CONTEST_RUNNING)
    var countdown = {{$contest_end_time - time()}};
    do_countdown();
  @else
    var countdown = 0;
  @endif
  function parseTime(t){
    const I = 60;
    const H = 60 * I;
    const D = 24 * H;
    const Y = 365 * D;

    var y = Math.floor(t / Y);
    t -= y * Y;

    var d = Math.floor(t / D);
    t -= d * D;

    var h = Math.floor(t / H);
    t -= h * H;

    var i = Math.floor(t / I);
    t -= i * I;

    var s = t;

    var ret = "";
    if(y) ret += y + "年";
    if(d) ret += d + "天";
    if(h) ret += h + "小时";
    if(i) ret += i + "分钟";
    if(s < 10) ret += "0";
    ret += s + "秒";
    return ret;
  }
  function do_countdown(){
    if(countdown > 0) setTimeout(do_countdown, 1000);
    $('#h-countdown').html(parseTime(countdown));
    countdown--;
  }
</script>
@endsection
