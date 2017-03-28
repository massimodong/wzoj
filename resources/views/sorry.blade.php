@extends ('layouts.master')

@section ('title')
{{ojoption('site_name')}}
@endsection

@section ('content')
<form method="POST" class="form-horizontal">
  {{csrf_field()}}
  <div class="form-group row">
    <label for="inputCaptcha" class="col-xs-2 control-label">{{trans('wzoj.captcha')}}</label>
    <div class="col-xs-4">
       <input type="text" class="form-control" id="inputCaptcha"
         name="captcha" placehold="{{trans('wzoj.captcha')}}" value="" required>
    </div>
    <div class="col-xs-6">
	<img src="{{captcha_src()}}" id="captchaImage" alt="captcha"
	  class="img-thumbnail" onclick="changeCaptcha()"></p>
    </div>
  </div>
  <div class="form-group">
    <div class="col-xs-offset-2 col-xs-10">
      <button type="submit" class="btn btn-primary">{{trans('wzoj.submit')}}</button>
    </div>
  </div>

</form>

@endsection
