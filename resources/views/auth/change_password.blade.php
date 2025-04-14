@extends ('layouts.master')

@section ('title')
{{ojoption('site_name')}}
@endsection

@section ('content')

<div class="modal fade" id="verifyPhoneModal" tabindex="-1" role="dialog" aria-labelledby="verifyPhoneModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="veryfyPhoneModalLabel">{{trans('wzoj.verify_phone')}}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form class="form-inline">
          <label class="sr-only" for="phone_number_verify2">{{trans('wzoj.phone')}}</label>
          <input type="text" class="form-control mb-2 mr-sm-2" id="phone_number_verify2" value="{{Auth::user()->phone_number}}" readonly>
          <button type="submit" class="btn btn-primary mb-2">{{trans('wzoj.send_verification_code')}}</button>
        </form>
        <form class="form-inline">
          <label class="sr-only" for="verification_code">{{trans('wzoj.verification_code')}}</label>
          <input type="text" class="form-control mb-2 mr-sm-2" id="verification_code" placeholder="">
          <button type="submit" class="btn btn-primary mb-2">{{trans('wzoj.verify_phone')}}</button>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{trans('wzoj.close')}}</button>
      </div>
    </div>
  </div>
</div>

<form method="POST" class="">
  {{csrf_field()}}
  <div class="form-group row">
    <label for="name" class="col-sm-2 col-form-label">{{trans('wzoj.username')}}</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="name" name="name" value="{{Auth::user()->name}}" required>
    </div>
  </div>
  <div class="form-group row">
    <label for="email" class="col-sm-2 col-form-label">{{trans('wzoj.email')}}</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="email" name="email" value="{{Auth::user()->email}}" required>
    </div>
  </div>
  <div class="form-group row">
    <label for="phone" class="col-sm-2 col-form-label">{{trans('wzoj.phone')}}</label>
    <div class="col-sm-8">
      <input type="text" class="form-control" id="phone" name="phone" value="{{Auth::user()->phone_number}}">
    </div>
    <div class="col-sm-2">
      @if (is_null(Auth::user()->phone_number) || empty(Auth::user()->phone_number))
      @elseif (Auth::user()->phone_number_verified)
        <span class="form-control-plaintext" style="color:green;">{{trans('wzoj.phone_verified')}}</span>
      @else
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#verifyPhoneModal">
          {{trans('wzoj.verify_phone')}}
        </button>
      @endif
    </div>
  </div>
  <div class="form-group row">
    <label for="new_password" class="col-sm-2 col-form-label">{{trans('wzoj.new_password')}}</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="new_password" name="new_password" autocomplete="new-password">
    </div>
  </div>
  <div class="form-group row">
    <label for="new_password_confirmation" class="col-sm-2 col-form-label">{{trans('wzoj.password_confirmation')}}</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" autocomplete="new-password">
    </div>
  </div>
  <hr>
  <div class="form-group row">
    <label for="old_password" class="col-sm-2 col-form-label">{{trans('wzoj.old_password')}}</label>
    <div class="col-sm-10">
      <input type="password" class="form-control" id="old_password" name="old_password" autocomplete="current-password">
    </div>
  </div>

  <div class="form-group row">
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-primary">{{trans('wzoj.submit')}}</button>
    </div>
  </div>
</form>

</div>
@endsection
