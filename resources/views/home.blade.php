@extends ('layouts.master')

@section ('title')
{{ojoption('site_name')}}
@endsection

@section ('content')
<div class="col-xs-9 row">
  @if (isset($home_diy))
    <div class="col-xs-12">
      {!! $home_diy->content !!}
    </div>
  @endif
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
  @if (isset($group_homeworks))
  <div class="col-xs-12">
    <h3>{{trans('wzoj.homework')}}</h3>
    <div style="width:795;padding:15px">
    <ul class="list-group">
      @foreach ($group_homeworks as $group_homework)
	  <li class="list-group-item row">
	    <div class="col-xs-6">
              <a href="/groups/{{$group_homework['group']->id}}/homework">{{$group_homework['group']->name}}</a>
	    </div>
	    <div class="col-xs-6">
	      <div class="progress">
	        <div class="progress-bar progress-bar-success progress-bar-striped" style="width:{{$group_homework['user_score']/$group_homework['total_score'] * 100}}%">
		    {{$group_homework['user_score']}}/{{$group_homework['total_score']}}
	        </div>
	      </div>
	    </div>
	  </li>
      @endforeach
    </ul>
    </div>
  </div>
  @endif
  <div class="col-xs-12">
    <h3>{{trans('wzoj.recent_contests')}}</h3>
    <div style="width:795;padding:15px">
    <ul class="list-group">
      @foreach ($recent_contests as $problemset)
	<li class="list-group-item row">
	  <div class="col-xs-4"><a href="/s/{{$problemset->id}}">{{$problemset->name}}</a></div>
	  <div class="col-xs-8">{{$problemset->contest_start_at}} - {{$problemset->contest_end_at}} {{trans('wzoj.problem_type_'.$problemset->type)}}
	    @if (strtotime($problemset->contest_start_at)<time())
	      <a href="/s/{{$problemset->id}}/ranklist">[{{trans('wzoj.ranklist')}}]</a>
	    @endif
	  </div>
	</li>
      @endforeach
    </ul>
    </div>
  </div>
  <div class="col-xs-12">
    <h3>{{trans('wzoj.user_rank_list')}}</h3>
    <hr>
    <table class="table table-striped table-bordered" id="user-ranklist-table">
      <thead>
        <tr>
	  <th style="width:7%">{{trans('wzoj.rank')}}</th>
	  <th style="width:15%">{{trans('wzoj.username')}}</th>
	  <th>{{trans('wzoj.user_description')}}</th>
	  <th style="width:10%">{{trans('wzoj.count_ac')}}</th>
	</tr>
      </thead>
    </table>
    <center><a href="/ranklist">{{trans('wzoj.user_rank_list')}}</a></center>
  </div>
  <div class="col-xs-12" style="height:50px"></div>
</div>
<div class="col-xs-3 row">
  @if (strlen(ojoption('logo_url')))
    <div class="col-xs-12">
      <img src="{{ojoption('logo_url')}}" class="navbar-logo" width="200" height="200">
    </div>
  @endif
  <div class="col-xs-12" style="height: 55px;"></div>
  @foreach ($sidePanels as $sidePanel)
    <div class="col-xs-12">
      <div class="panel panel-wzoj">
        <div class="panel-heading"> {{$sidePanel->title}} </div>
        <div class="panel-body">{!!$sidePanel->content!!}</div>
      </div>
    </div>
  @endforeach
  @if (Auth::check())
    @foreach ($groups as $group)
      @if (strlen($group->notice))
	<div class="col-xs-12">
          <div class="panel panel-wzoj">
            <div class="panel-heading">{{$group->name}}-{{trans('wzoj.notice')}}</div>
            <div class="panel-body">{{$group->notice}}</div>
          </div>
	</div>
      @endif
    @endforeach
  @endif
</div>
@endsection

@section ('scripts')
<script>
jQuery(document).ready(function($) {
	$(".problemset-dock").click(function() {
		window.document.location = $(this).data("href");
	});
	$('#user-ranklist-table').DataTable( {
		ajax: '/ajax/top-users',
		searching: false,
		ordering: false,
		paging: false,
		bInfo: false,
		columns: [
		    {   render: function ( data, type, row, meta){
				return meta.row+1;
		        }
		    },
		    { data: 'name',
			render: function ( data, type, row, meta){
				return "<a href='/users/" + row.id + "'>" + escapeHtml(data) + "</a>";
			}
		    },
		    { data: 'description',
			render: function ( data, type, row, meta){
				return "<div style='overflow-y: auto;max-height: 58px'>" + escapeHtml(data) + "</div>";
			}
		    },
		    { data: 'cnt_ac' },
		],
	} );
});
</script>
@endsection
