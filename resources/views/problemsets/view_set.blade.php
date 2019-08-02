@extends ('layouts.master')

@section ('title')
{{$problemset->name}}
@endsection

@section ('content')
{!! Breadcrumbs::render('problemset', $problemset) !!}
<h1 class='page-header text-center'>{{$problemset->name}}</h1>
@can ('update', $problemset)
<div class="pull-right"><a href="/s/{{$problemset->id}}/edit">{{trans('wzoj.edit')}}</a></div>
@endif

{!! $problemset->description !!}

<nav aria-label="pagination">
  <ul class="pagination">
    @for ($i=1;$i <= $cnt_pages;++$i)
      @if ($i == $cur_page)
        <li class="page-item active"><a class="page-link" href="/s/{{$problemset->id}}?page={{$i}}">{{$i}}<span class="sr-only">(current)</span></a></li>
      @else
        <li class="page-item"><a class="page-link" href="/s/{{$problemset->id}}?page={{$i}}">{{$i}}</a></li>
      @endif
    @endfor
  </ul>
</nav>

<div class="table-responsive">
  <table class="table">
    <thead>
      <tr>
        <th></th>
        <th>{{trans('wzoj.index')}}</th>
        <th>{{trans('wzoj.name')}}</th>
        <th>{{trans('wzoj.tags')}}</th>
        <th>{{trans('wzoj.source')}}</th>
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
      <td>
        @if ($problemset->show_problem_tags)
        <span>
          @include ('partials.problem_tags', ['problem' => $problem])
        </span>
        @endif
      </td>
      <td>{{$problem->source}}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

<nav aria-label="pagination">
  <ul class="pagination">
    @for ($i=1;$i <= $cnt_pages;++$i)
      @if ($i == $cur_page)
        <li class="page-item active"><a class="page-link" href="/s/{{$problemset->id}}?page={{$i}}">{{$i}}<span class="sr-only">(current)</span></a></li>
      @else
        <li class="page-item"><a class="page-link" href="/s/{{$problemset->id}}?page={{$i}}">{{$i}}</a></li>
      @endif
    @endfor
  </ul>
</nav>

@endsection
