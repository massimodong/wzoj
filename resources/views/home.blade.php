@extends ('layouts.master')

@section ('title')
{{ojoption('site_name')}}
@endsection

@section ('content')
<div class="col-xs-9 row">
  <div class="col-xs-12 row">
  @foreach ($home_page_problemsets as $problemset)
    <div class="col-xs-4">
      <div class="thumbnail problemset-dock" data-href="/s/{{$problemset->id}}">
        <div class="row">
          <div class="col-xs-12">
            <div style="padding: 9px;min-height: 130px;">
              <div class="dock-heading">{{$problemset->name}}</div>
  		{!! Purifier::clean($problemset->description) !!}
	    </div>
          </div>
        </div>
      </div>
    </div>
  @endforeach
  </div>
  <div class="col-xs-12">
    <h3>{{trans('wzoj.recent_contests')}}</h3>
    <ul class="list-group">
      @foreach ($recent_contests as $problemset)
	<li class="list-group-item row">
	  <div class="col-xs-5"><a href="/s/{{$problemset->id}}">{{$problemset->name}}</a></div>
	  <div class="col-xs-7">{{$problemset->contest_start_at}} - {{$problemset->contest_end_at}} {{trans('wzoj.problem_type_'.$problemset->type)}}</div>
	</li>
      @endforeach
    </ul>
  </div>
  <div class="col-xs-12">
    <h3>{{trans('wzoj.user_rank_list')}}</h3>
    <hr>
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
	  <th style="width:7%">{{trans('wzoj.rank')}}</th>
	  <th style="width:15%">{{trans('wzoj.username')}}</th>
	  <th>{{trans('wzoj.user_description')}}</th>
	  <th style="width:10%">{{trans('wzoj.count_ac')}}</th>
	</tr>
      </thead>
      <tbody>
        @foreach ($top_users as $key=>$user)
	<tr>
	  <td>{{$key + 1}}</td>
	  <td><a href="/users/{{$user->id}}">{{$user->name}}</a></td>
	  <td><div style="overflow-y: auto;max-height: 58px">{{$user->description}}</div></td>
	  <td>{{$user->cnt_ac}}</td>
	</tr>
	@endforeach
      </tbody>
    </table>
    <center><a href="/ranklist">{{trans('wzoj.user_rank_list')}}</a></center>
  </div>
  <div class="col-xs-12" style="height:50px"></div>
</div>
<div class="col-xs-3 row">
  <div class="panel panel-wzoj">
    <div class="panel-heading">{{trans('wzoj.notices')}}</div>
    <div class="panel-body">
	{{trans('wzoj.none')}}
    </div>
  </div>
</div>
@endsection

@section ('scripts')
<script>
jQuery(document).ready(function($) {
	$(".problemset-dock").click(function() {
		window.document.location = $(this).data("href");
	});
});
</script>
@endsection
