@extends ('layouts.master')

@section ('title')
{{$user->fullname}}
@endsection

@section ('content')
{!! Breadcrumbs::render('user', $user) !!}

<input type="file" id="image_upload" accept="image/*" style="display: none"/>

<!-- Modal -->
<div class="modal fade" id="configModal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="configModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="configModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-4">
              <div class="row">
                <div class="col-12">
                  <div id="avatar_image"></div>
                </div>
                <div class="col-12">
                  <div id="avatar_image_msg" onclick="$('#image_upload').trigger('click')">
                    <div id="avatar_image_msg_2">{{trans('wzoj.change_avatar')}}</div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-lg-8">
              <form id="configForm" method='POST' enctype='multipart/form-data'>
                {{csrf_field()}}
                <input id="avatar" name="avatar" style="display: none"/>
                <input id="fullname_lock" name="fullname_lock" value="0" style="display: none"/>
                <input id="class_lock" name="class_lock" value="0" style="display: none"/>
                <div class="form-row">
                  <div class="form-group col-md-6">
                    <div class="input-group">
                      <input type="text" class="form-control" id="fullname" name="fullname" value="{{$user->fullname}}" placeholder="{{trans('wzoj.fullname')}}"
                      @can ('change_fullname', $user)
                      @else
                      disabled
                      @endcan
                      >
                      @can ('change_lock', $user)
                      <div class="input-group-append">
                        <span class="input-group-text"><span id="fullname_lock_icon" onclick="change_lock(this);" class="fa fa-{{$user->fullname_lock?'lock':'unlock'}}"></span></span>
                      </div>
                      @endcan
                    </div>
                  </div>
                  <div class="form-group col-md-6">
                    <div class="input-group">
                      <input type="text" class="form-control" id="class" name="class" value="{{$user->class}}" placeholder="{{trans('wzoj.class')}}"
                      @can ('change_class', $user)
                      @else
                      disabled
                      @endcan
                      >
                      @can ('change_lock', $user)
                      <div class="input-group-append">
                        <span class="input-group-text"><span id="class_lock_icon" onclick="change_lock(this);" class="fa fa-{{$user->class_lock?'lock':'unlock'}}"></span></span>
                      </div>
                      @endcan
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <textarea class="form-control" id="description" name="description" rows="5"
                    @can ('change_description', $user)
                    @else
                      disabled
                    @endcan
                  >{{$user->description}}</textarea>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="$('#image_upload').trigger('click')">{{trans('wzoj.choose_avatar')}}</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('wzoj.close')}}</button>
        <button type="button" class="btn btn-primary" onclick="configForm_submit();">{{trans('wzoj.submit')}}</button>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12 col-lg-3">
    <img src="{{$user->avatar_url('lg')}}" class="mr-3">
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
        {{trans('wzoj.last_login_time')}}: {{date("Y-m-d",strtotime($user->last_login_at))}}<br>
        {{trans('wzoj.count_submit')}}/{{trans('wzoj.count_ac_problems')}}:
          <a href="/solutions?user_name={{$user->name}}">{{$cnt_submissions}}</a> / <a href="/solutions?user_name={{$user->name}}&score_min=100&score_max=100">{{$user->cnt_ac}}</a><br>
        {{trans('wzoj.register_time')}}: {{date("Y-m-d",strtotime($user->created_at))}}
      </div>
    </div>
  </div>
  <div class="col-12 my-2">
    <div class="card">
      <div class="card-header">
        {{trans('wzoj.user_description')}}
      </div>
      <div class="card-body">
        {{$user->description}}
      </div>
    </div>
  </div>
  <div class="col-12">
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#configModal">
      {{trans('wzoj.edit_profile')}}
    </button>
    @if (Auth::check() && Auth::user()->id == $user->id)
    <a href="/password/change">
      <button type="button" class="btn btn-primary">
        {{trans('wzoj.change_password')}}
      </button>
    </a>
    @endif
  </div>
</div>

@endsection

@section ('scripts')
<script>
function readFile(input){
  if(input.files && input.files[0]){
    $('#avatar_image').addClass('ready');
    $('#avatar_image_msg').addClass('ready');
    var reader = new FileReader();
    reader.onload = function(e){
      uploadCrop.croppie('bind', {
        url: e.target.result,
        zoom: 0
      }).then(function(){
      });
      $('#configModal').modal('handleUpdate');
    }
    reader.readAsDataURL(input.files[0]);
  }else{
  }
}
uploadCrop = $('#avatar_image').croppie({
  viewport: {
    width: 190,
    height: 190,
  },
  boundary: {
    width: 230,
    height: 230
  }
});
$('#image_upload').on('change', function () { readFile(this); });

function configForm_submit(){
  if($('#fullname_lock_icon').hasClass('fa-lock')){
    $('#fullname_lock').val(1);
  }
  if($('#class_lock_icon').hasClass('fa-lock')){
    $('#class_lock').val(1);
  }
  if($('#avatar_image').hasClass('ready')){
    uploadCrop.croppie('result', 'base64').then(function(result){
      $('#avatar').val(result);
      $('#configForm').submit();
    });
  }else{
    $('#configForm').submit();
  }
}

function change_lock(lock){
  if($(lock).hasClass('fa-lock')){
    $(lock).removeClass('fa-lock');
    $(lock).addClass('fa-unlock');
  }else{
    $(lock).removeClass('fa-unlock');
    $(lock).addClass('fa-lock');
  }
}
</script>
@endsection
