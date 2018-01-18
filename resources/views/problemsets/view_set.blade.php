@extends ('layouts.master')

@section ('title')
{{$problemset->name}}
@endsection

@section ('content')

<h1 class='page-header text-center'>{{$problemset->name}}</h1>
@can ('update', $problemset)
<div class="pull-right"><a href="/s/{{$problemset->id}}/edit">{{trans('wzoj.edit')}}</a></div>
@endif

<div id="home" class="tab-pane fade in active">
  {!! $problemset->description !!}

  <center><ul class="pagination">
    @for ($i=1;$i <= $cnt_pages;++$i)
      <li {{$i == $cur_page ? "class=active":""}}><a href="/s/{{$problemset->id}}?page={{$i}}">{{$i}}</a></li>
    @endfor
  </ul></center>

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
        @if (isset($max_scores[$problem->id]) && $max_scores[$problem->id] >= 100)
	  <span class="glyphicon glyphicon-ok" style="color:green"></span>
        @elseif (isset($max_scores[$problem->id]) && $max_scores[$problem->id] >= 0)
          <span style="color:red">
	  {{$max_scores[$problem->id]}}</span>
	@endif
      </td>
      <td>{{$problem->pivot->index}}</td>
      <td><a href='/s/{{$problemset->id}}/{{$problem->id}}'>{{$problem->name}}</a>
        @if ($problemset->show_problem_tags)
        <span class="pull-right">
	  @include ('layouts.problem_tags', ['problem' => $problem])
        </span>
        @endif
      </td>
      <td>{{$problem->source}}</td>
    </tr>
    @endforeach
  </tbody>
  </table>

  <center><ul class="pagination">
    @for ($i=1;$i <= $cnt_pages;++$i)
      <li {{$i == $cur_page ? "class=active":""}}><a href="/s/{{$problemset->id}}?page={{$i}}">{{$i}}</a></li>
    @endfor
  </ul></center>
</div>
<!-- home -->

@endsection
