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
      <td>{{$start_rank++}}</td>
      <td>@include ('partials.user_badge', ['user' => $user])</td>
      <td><div style="overflow-y: auto;max-height: 58px">{{$user->description}}</div></td>
      <td>{{$user->cnt_ac}}</td>
    </tr>
  @endforeach
  </tbody>
</table>
</div>

{{$users->onEachSide(0)->links()}}
@endsection
