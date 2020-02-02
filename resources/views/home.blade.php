@extends ('layouts.master')

@section ('title')
{{ojoption('site_name')}}
@endsection

@section ('content')
<div class="row">
  <div class="col-xl-9 col-md-8">
    @if (isset($home_diy))
      {!! $home_diy->content !!}
    @endif
    @if (isset($group_homeworks))
      <div class="card my-3">
        <div class="card-header">{{trans('wzoj.homeworks')}}</div>
        <ul class="list-group list-group-flush">
        @foreach ($group_homeworks as $group_homework)
          <li class="list-group-item">
            <div class="row">
              <a class="col-xl-6" href="/groups/{{$group_homework['group']->id}}/homework">{{$group_homework['group']->name}}</a>
              <div class="col-xl-6">
                <div class="progress" style="height: 24px;">
                  <div class="progress-bar bg-success" role="progressbar" style="width: {{100 * $group_homework['user_score'] / $group_homework['total_score']}}%"
                  aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">{{$group_homework['user_score']}} / {{$group_homework['total_score']}}</div>
                </div>
              </div>
            </div>
          </li>
        @endforeach
        </ul>
      </div>
    @endif
    @if ($view_history->count())
      <div class="card my-3">
        <div class="card-header">{{trans('wzoj.view_history')}}</div>
        <ul class="list-group list-group-flush">
        @foreach ($view_history as $history)
          <li class="list-group-item">
            <a href="/s/{{$history['psid']}}/{{$history['pid']}}">{{$history["pn"]}}</a> -- <a href="/s/{{$history['psid']}}">{{$history["psn"]}}</a>
          </li>
        @endforeach
        </ul>
      </div>
    @endif
    <div class="card my-3">
      <div class="card-header">{{trans('wzoj.recent_contests')}}</div>
      <ul class="list-group list-group-flush">
      @foreach ($recent_contests as $problemset)
        <li class="list-group-item">
          <div class="row">
            <div class="col-xl-6 col-sm-12">
              <a href="/s/{{$problemset->id}}">{{$problemset->name}}</a>
            </div>
            <div class="col-xl-3 col-sm-6">
              <span style="color:green">{{$problemset->contest_start_at}}</span>
            </div>
            <div class="col-xl-3 col-sm-6">
              <span style="color:red">{{$problemset->contest_end_at}}</span>
            </div>
          </div>
        </li>
      @endforeach
      </ul>
    </div>
  </div>
  <div class="col-xl-3 col-md-4">
    <form action="/search" method="GET">
      <div class="d-table-cell w-100">
        <input type="text" class="form-control" name="name" placeholder="{{trans('wzoj.search')}}">
      </div>
      <div class="d-table-cell align-middle">
        <button type="button" class="btn btn-light dropdown-toggle dropdown-toggle-split" data-toggle="collapse" data-target="#search_options" aria-expanded="false" aria-controls="search_options">
          <span class="sr-only">Toggle Dropdown</span>
        </button>
      </div>
      <div class="collapse" id="search_options">
        <div class="card px-0 py-0"><div class="card-body">
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="search_item" id="search_problems" value="problems" checked>
            <label class="form-check-label" for="search_problems">{{trans('wzoj.problems')}}</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="radio" name="search_item" id="search_users" value="users">
            <label class="form-check-label" for="search_users">{{trans('wzoj.users')}}</label>
          </div>
          <div class="form-group">
            <select id="tags-select" name="tags[]" class="selectpicker form-control" data-live-search="true" title="{{trans('wzoj.tags')}}" multiple>
            @foreach (\App\ProblemTag::all() as $tag)
              <option data-tokens="{{$tag->aliases}}" value="{{$tag->id}}">{{$tag->name}}</option>
            @endforeach
            </select>
          </div>
          <button class="btn btn-primary" type="submit">{{trans('wzoj.search')}}</button>
        </div></div>
      </div>
    </form>
    <div class="overflow-auto" id="home_sidebar">
    @foreach ($sidePanels as $sidePanel)
      <div class="py-1"><div class="card">
        <div class="card-body">
          <h5 class="card-title">{{$sidePanel->title}}</h5>
          <p class="card-text">{!!$sidePanel->content!!}</p>
        </div>
      </div></div>
    @endforeach
    </div>
  </div>
</div>
@endsection

@section ('scripts')
<script>
$(document).ready(function(){
  $("#search_problems").change(function(){
    $('#tags-select').prop("disabled", false);
    $('#tags-select').selectpicker("refresh");
  });
});
$(document).ready(function(){
  $("#search_users").change(function(){
    $('#tags-select').prop("disabled", true);
    $('#tags-select').selectpicker("refresh");
  });
});
</script>
@endsection
