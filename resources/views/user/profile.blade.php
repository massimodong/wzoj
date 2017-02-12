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
							<a href="#" target="_blank">
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
			<div id="overview" class="tab-pane in active">
				todo
			</div>
			<div id="settings" class="tab-pane">

				<form method='POST'>
				{{csrf_field()}}

				<div class="form-group">
    					<label for="email"> {{trans('wzoj.email')}}: </label>
					<input type="email" class="form-control" id="email" name="email" value="{{$user->email}}" disabled>
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
				<button type="submit" class="btn btn-primary">{{trans('wzoj.submit')}}</button>

				</form>
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
});
</script>
@endsection
