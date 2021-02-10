@extends ('layouts.master')

@section ('title')
{{ojoption('site_name')}}
@endsection

@section ('content')
{!! Breadcrumbs::render('ranklist') !!}
<div class="table-responsive">
<table class="table table-bordered">
  <thead>
    <tr>
      <th>{{trans('wzoj.rank')}}</th>
      <th>{{trans('wzoj.username')}}</th>
      <th>{{trans('wzoj.user_description')}}</th>
      <th>{{trans('wzoj.count_ac')}}</th>
    </tr>
  </thead>
  <tbody>
  @foreach ($users as $user)
    <tr>
      <td>{{++$start_rank}}</td>
      <td>@include ('partials.user_badge', ['user' => $user])</td>
      <td><div style="overflow-y: auto;max-height: 58px">{{$user->description}}</div></td>
      <td>{{$user->cnt_ac}}</td>
    </tr>
  @endforeach
  </tbody>
</table>
</div>

<nav>
  <ul class="pagination">
    @if ($cur_page != 1)
      <li class="page-item"><a class="page-link" href="/ranklist">{{trans('wzoj.toppage')}}</a></li>
    @endif
    @for ($p = max($cur_page - 2, 1); $p <= min($cur_page + 2, $max_page); ++$p)
      @if ($p == $cur_page)
        <li class="page-item active" aria-current="page">
          <a class="page-link" href="/ranklist?page={{$p}}">{{$p}}<span class="sr-only">(current)</span></a>
        </li>
      @else
        <li class="page-item"><a class="page-link" href="/ranklist?page={{$p}}">{{$p}}</a></li>
      @endif
    @endfor
    @if ($cur_page != $max_page)
      <li class="page-item"><a class="page-link" href="/ranklist?page={{$max_page}}">{{trans('wzoj.bottompage')}}</a></li>
    @endif
  </ul>
</nav>
@endsection
