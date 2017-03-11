@extends ('layouts.master')

@section ('title')
{{$user->fullname}}
@endsection

@section ('content')
	<div class="row profile">
		<div class="col-md-3">
			<div class="profile-sidebar">
				<!-- SIDEBAR USERPIC -->
				<div class="profile-userpic">
					<img src="//cn.gravatar.com/avatar/{{md5(strtolower(trim($user->email)))}}?d=mm&s=256" class="img-responsive" alt="">
				</div>
				<!-- END SIDEBAR USERPIC -->
				<!-- SIDEBAR USER TITLE -->
				<div class="profile-usertitle">
					<div class="profile-usertitle-name">
						{{$user->fullname}}
					</div>
					<div class="profile-usertitle-job">
						{{$user->name}}
					</div>
					<div class="profile-usertitle-description">{{$user->description}}</div>
				</div>
				<!-- END SIDEBAR USER TITLE -->
				<!-- SIDEBAR MENU -->
				<div class="profile-usermenu">
					<ul class="nav">
						<li class="active">
							<a data-toggle="tab" href="#overview">
							<i class="glyphicon glyphicon-home"></i>
							{{trans('wzoj.overview')}} </a>
						</li>
						<li>
							<a data-toggle="tab" href="#settings">
							<i class="glyphicon glyphicon-user"></i>
							{{trans('wzoj.settings')}} </a>
						</li>
						<li>
							<a href="#">
							<i class="glyphicon glyphicon-ok"></i>
							{{trans('wzoj.homeworks')}} </a>
						</li>
						<li>
							<a href="#">
							<i class="glyphicon glyphicon-flag"></i>
							{{trans('wzoj.help')}} </a>
						</li>
					</ul>
				</div>
				<!-- END MENU -->
			</div>
		</div>
		<div class="col-md-9">
            <div class="profile-content">
	    	<div class="tab-content">
			<div id="overview" class="tab-pane in active row">
				<div class="col-xs-4" style="height:250px;">
					<div class="panel panel-wzoj">
					<div class="panel-heading">{{trans('wzoj.overview')}}</div>
					<div class="panel-body">
					{{trans('wzoj.count_submit')}}:<span class="pull-right">{{$cnt_submissions}}</span><br>
					{{trans('wzoj.count_ac_problems')}}:<span class="pull-right">{{$user->cnt_ac}}</span><br>
					{{trans('wzoj.register_time')}}:<span class="pull-right">
						{{date("Y-m-d",strtotime($user->created_at))}}</span><br>
					{{trans('wzoj.last_login_time')}}:<span class="pull-right">NULL</span><br>
					{{trans('wzoj.belong_groups')}}:<span class="pull-right">
					@if (count($user->groups) == 0)
						{{trans('wzoj.none')}}
					@elseif (count($user->groups) == 1)
						{{$user->groups[0]->name}}
					@else
						<span title="{{trans('wzoj.belong_groups')}}:@foreach ($user->groups as $key => $group){{$key?', ':''}}{{$group->name}}@endforeach">{{$user->groups[0]->name.' '.trans('wzoj.ect')}}</span>
					@endif
						</span><br>
					</div>
					</div>
				</div>
				<div class="col-xs-8" style="height:250px;">
					<canvas id="activity_chart" style="width:100%;height:100%"></canvas>
				</div>
				<div class="col-xs-12" style="overflow-y: scroll;height:150px">
				<table class="table table-striped">
				<thead>
					<tr>
						<th style="width:9%">{{trans('wzoj.id')}}</th>
						<th style="width:12%">{{trans('wzoj.problem')}}</th>
						<th style="width:12%">{{trans('wzoj.score')}}</th>
						<th style="width:9%">{{trans('wzoj.time_used')}}</th>
						<th style="width:16%">{{trans('wzoj.memory_used')}}</th>
						<th style="width:11%">{{trans('wzoj.language')}}</th>
						<th style="width:12%">{{trans('wzoj.code_length')}}</th>
						<th style="width:19%">{{trans('wzoj.judged_at')}}</th>
					</tr>
				</thead>
				<tbody>
					@foreach ($last_solutions as $solution)
					<tr class='clickable-row' data-href='/solutions/{{$solution->id}}'>
						<td>{{$solution->id}}</td>
						<td>{{$solution->problem->name}}</td>
						<td>{{$solution->score}}</td>
						<td>{{$solution->time_used}}ms</td>
						<td>{{sprintf('%.2f', $solution->memory_used / 1024 / 1024)}}MB</td>
						<td>{{trans('wzoj.programing_language_'.$solution->language)}}</td>
						<td>{{$solution->code_length}}B</td>
						<td>{{$solution->judged_at}}</td>
					</tr>
					@endforeach
				</tbody>
				</table>
				<center><a href="/solutions?user_name={{$user->name}}">{{trans('wzoj.more')}}</a></center>
				<div style="height:20px"></div>
				</div>
			</div>
			<div id="settings" class="tab-pane">

			    <form method='POST'>
				{{csrf_field()}}

				<div class="form-group">
    					<label for="email"> {{trans('wzoj.email')}}: </label>
					<input type="email" class="form-control" id="email" name="email" value="{{substr($user->email, 0, 1).'****'.substr($user->email, strpos($user->email, '@'))}}" disabled>
				</div>
				<div class="form-group">
    					<label for="fullname"> {{trans('wzoj.fullname')}}: </label>
					<input type="text" class="form-control" id="fullname" name="fullname" value="{{$user->fullname}}"
					@can ('change_fullname' , $user)
					@else
					disabled
					@endcan
					>
				</div>

				@can ('change_lock' , $user)
				<label class="custom-control custom-checkbox">
					<input type="checkbox" class="custom-control-input" name="fullname_lock" value="1" {{$user->fullname_lock?"checked":""}}>
					<span class="custom-control-indicator"></span>
					<span class="custom-control-description">{{trans('wzoj.lock_fullname')}}</span>
				</label>
				@endcan

				<div class="form-group">
    					<label for="class"> {{trans('wzoj.class')}}: </label>
					<input type="text" class="form-control" id="class" name="class" value="{{$user->class}}"
					@can ('change_class' , $user)
					@else
					disabled
					@endcan
					>
				</div>

				@can ('change_lock' , $user)
				<label class="custom-control custom-checkbox">
					<input type="checkbox" class="custom-control-input" name="class_lock" value="1" {{$user->class_lock?"checked":""}}>
					<span class="custom-control-indicator"></span>
					<span class="custom-control-description">{{trans('wzoj.lock_class')}}</span>
				</label>
				@endcan
				<div class="form-group">
    					<label for="class"> {{trans('wzoj.description')}}: </label>
					<textarea class="form-control" id="description" name="description"
					@can ('change_description', $user)
					@else
					disabled
					@endcan
					>{{$user->description}}</textarea>
				</div>
				<button type="submit" class="btn btn-primary">{{trans('wzoj.submit')}}</button>

			    </form>
			    @if (Auth::check() && $user->id == Auth::user()->id)
			    <a href="/password/change">{{trans('wzoj.change_password')}}</a>
			    @endif
			</div>
		</div>
            </div>
		</div>
	</div>

@endsection

@section ('scripts')
<script>
selectHashTab();
jQuery(document).ready(function($) {

	$(".clickable-row").click(function() {
                window.document.location = $(this).data("href");
        });

	var ctx = $("#activity_chart").get(0).getContext("2d");
	var data = {
	labels : [
	@for ($i=0;$i<$month_cnt;++$i)
		"{{trans('wzoj.month_'.$month_no[$i])}}"
		{{$i < $month_cnt - 1?",":""}}
	@endfor
	],
	datasets : [
		{
			label : "{{trans('wzoj.count_submit')}}",
			backgroundColor : "rgba(220,220,220,0.5)",
			borderColor : "rgba(220,220,220,1)",
			pointBackgroundColor : "rgba(220,220,220,1)",
			pointBorderColor : "#fff",
			data : [
			@for ($i=0;$i<$month_cnt;++$i)
				{{$month_submit_cnt[$i]}}
				{{$i < $month_cnt - 1?",":""}}
			@endfor
			]
		},
		{
			label : "{{trans('wzoj.count_ac')}}",
			backgroundColor : "rgba(151,187,205,0.5)",
			borderColor : "rgba(151,187,205,1)",
			pointBackgroundColor : "rgba(151,187,205,1)",
			pointBorderColor : "#fff",
			data : [
			@for ($i=0;$i<$month_cnt;++$i)
				{{$month_ac_cnt[$i]}}
				{{$i < $month_cnt - 1?",":""}}
			@endfor

			]
		}
	]
}
	var options = {
		scales: {
			yAxes: [{
				ticks: {
					beginAtZero: true,
					suggestedMax: 5
				}
			}]
		}
    	};
	var myLineChart = new Chart(ctx, {
		type: 'line',
		data: data,
		options: options
	});
});
</script>
@endsection
