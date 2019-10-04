@extends ('layouts.master')

@section ('title')
{{trans('wzoj.edit')}}
@endsection

@section ('content')
<form action="/users/{{$user->id}}" method="POST">
  {{csrf_field()}}
  <div class="form-group row">
    <label for="fullname" class="col-sm-2 control-label">{{trans('wzoj.fullname')}}:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="fullname" name="fullname" value="{{$user->fullname}}"
      @can ('change_fullname', $user)
      @else
      disabled
      @endcan
      >
    </div>
  </div>

  @can ('change_lock' , $user)
    <div class="custom-control custom-checkbox">
      <input type="checkbox" class="custom-control-input" id="fullname_lock" name="fullname_lock" value="1" {{$user->fullname_lock?"checked":""}}>
      <label class="custom-control-label" for="fullname_lock">{{trans('wzoj.lock_fullname')}}</label>
    </div>
  @endcan

  <div class="form-group row">
    <label for="class" class="col-sm-2 control-label">{{trans('wzoj.class')}}:</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="class" name="class" value="{{$user->class}}"
      @can ('change_class', $user)
      @else
      disabled
      @endcan
      >
    </div>
  </div>

  @can ('change_lock' , $user)
    <div class="custom-control custom-checkbox">
      <input type="checkbox" class="custom-control-input" id="class_lock" name="class_lock" value="1" {{$user->class_lock?"checked":""}}>
      <label class="custom-control-label" for="class_lock">{{trans('wzoj.lock_class')}}</label>
    </div>
  @endcan

  <div class="form-group row">
    <label for="description" class="col-sm-2 control-label">{{trans('wzoj.description')}}:</label>
    <div class="col-sm-10">
      <textarea class="form-control" id="description" name="description" rows="5"
      @can ('change_description', $user)
      @else
      disabled
      @endcan
      >{{$user->description}}</textarea>
    </div>
  </div>
  <button type="submit" class="btn btn-primary">{{trans('wzoj.submit')}}</button>
  @if (Auth::check() && $user->id == Auth::user()->id)
    <a href="/password/change">{{trans('wzoj.change_password')}}</a>
  @endif
</form>
@endsection
