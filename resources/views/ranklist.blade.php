@extends ('layouts.master')

@section ('title')
{{ojoption('site_name')}}
@endsection

@section ('content')
<table class="table table-striped table-bordered">
  <thead>
    <tr>
      <th style="width: 7%">{{trans('wzoj.rank')}}</th>
      <th style="width: 15%">{{trans('wzoj.username')}}</th>
      <th>{{trans('wzoj.user_description')}}</th>
      <th style="width: 10%">{{trans('wzoj.count_ac')}}</th>
    </tr>
  </thead>
  <tbody>
  @foreach ($users as $user)
    <tr>
      <td>{{++$start_rank}}</td>
      <td><a href="/users/{{$user->id}}">{{$user->name}}</a></td>
      <td><div style="overflow-y: auto;max-height: 58px">{{$user->description}}</div></td>
      <td>{{$user->cnt_ac}}</td>
    </tr>
  @endforeach
  </tbody>
</table>
<center>
  <ul class="pagination">
    @for ($p = $cur_page - 5;$p < $cur_page;++$p)
    	@if ($p > 0)
	  <li><a href="/ranklist?page={{$p}}">{{$p}}</a></li>
	@endif
    @endfor
    <li class="active"><a href="/ranklist?page={{$cur_page}}">{{$cur_page}}</a></li>
    @for ($p = $cur_page + 1;$p < $cur_page + 5;++$p)
    	@if ($p <= $max_page)
	  <li><a href="/ranklist?page={{$p}}">{{$p}}</a></li>
	@endif
    @endfor
  </ul>
</center>
@endsection
