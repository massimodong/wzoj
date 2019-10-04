@extends ('layouts.master')

@section ('title')
{{$user->fullname}}
@endsection

@section ('content')
{!! Breadcrumbs::render('user', $user) !!}

<div class="row">
  <div class="col-12 col-lg-3">
    <img src="//cn.gravatar.com/avatar/{{md5(strtolower(trim($user->email)))}}?d=retro&s=205" class="mr-3">
    <div class="buffer-sm"></div>
  </div>
  <div class="col-12 col-lg-9">
    <div class="card">
      <div class="card-body">
        <h4 class="card-title">{{$user->fullname}}</h4>
        <h5 class="card-subtitle mb-2 text-muted">{{$user->name}}, {{$user->class}}</h5>
        {{trans('wzoj.belong_groups')}}:
          @if (count($groups) == 0)
						{{trans('wzoj.none')}}
					@elseif (count($groups) == 1) {{$groups[0]->name}}
					@else
            <span title="{{trans('wzoj.belong_groups')}}:@foreach ($groups as $key => $group){{$key?', ':''}}{{$group->name}}@endforeach">{{$groups[0]->name.' '.trans('wzoj.ect')}}</span>
					@endif <br>
        {{trans('wzoj.last_login_time')}}: NULL<br>
        {{trans('wzoj.count_submit')}}/{{trans('wzoj.count_ac_problems')}}:
          <a href="/solutions?user_name={{$user->name}}">{{$cnt_submissions}}</a> / <a href="/solutions?user_name={{$user->name}}&score_min=100&score_max=100">{{$user->cnt_ac}}</a><br>
        {{trans('wzoj.register_time')}}: {{date("Y-m-d",strtotime($user->created_at))}}
      </div>
    </div>
  </div>
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        {{trans('wzoj.user_description')}}
      </div>
      <div class="card-body">
        {{$user->description}}
      </div>
    </div>
  </div>
</div>
@endsection
