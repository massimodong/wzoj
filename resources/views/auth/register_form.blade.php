@extends ('layouts.master')

@section ('title')
{{trans('wzoj.register')}}
@endsection

@section ('content')
<form method="POST" action="/auth/register?token={{$invitation->token}}" class="form-register">
  {!! csrf_field() !!}
  <div class="form-group row">
    <label for="name" class="col-sm-2 col-form-label">{{trans('wzoj.username')}}</label>
    <div class="col-sm-10">
      <input class="form-control" type="text" name="name" id="name" value="{{old('name')}}" required>
    </div>
  </div>

  @if ($invitation->fullname <> '')
  <div class="form-group row">
    <label for="fullname" class="col-sm-2 col-form-label">{{trans('wzoj.fullname')}}</label>
    <div class="col-sm-10">
      <input class="form-control" type="text" name="fullname" id="fullname" value="{{$invitation->fullname}}" disabled>
    </div>
  </div>
  @endif

  @if ($invitation->class <> '')
  <div class="form-group row">
    <label for="class" class="col-sm-2 col-form-label">{{trans('wzoj.class')}}</label>
    <div class="col-sm-10">
      <input class="form-control" type="text" name="class" id="class" value="{{$invitation->class}}" disabled>
    </div>
  </div>
  @endif

  <div class="form-group row">
    <label for="email" class="col-sm-2 col-form-label">{{trans('wzoj.email')}}</label>
    <div class="col-sm-10">
      <input class="form-control" type="email" name="email" id="email" value="{{old('email')}}" required>
    </div>
  </div>

  <div class="form-group row">
    <label for="password" class="col-sm-2 col-form-label">{{trans('wzoj.password')}}</label>
    <div class="col-sm-10">
      <input class="form-control" type="password" name="password" id="password" value="{{old('password')}}" required>
    </div>
  </div>

 <div class="form-group row">
    <label for="password_confirmation" class="col-sm-2 col-form-label">{{trans('wzoj.password_confirmation')}}</label>
    <div class="col-sm-10">
      <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" value="{{old('password_confirmation')}}" required>
    </div>
  </div>

  <div class="form-group row">
    <label for="inputCaptcha" class="col-sm-2 col-form-label">{{trans('wzoj.captcha')}}</label>
    <div class="col-sm-4">
       <input type="text" class="form-control" id="inputCaptcha"
         name="captcha" placeholder="{{trans('wzoj.captcha')}}" value="" required>
    </div>
    <div class="col-sm-6">
        <img src="/_captcha/default?{{rand()}}" id="captchaImage" alt="captcha"
          class="img-thumbnail" onclick="changeCaptcha()"></p>
    </div>
  </div>
  <button type="submit" class="btn btn-primary">{{trans('wzoj.register')}}</button>
</form>
@endsection
