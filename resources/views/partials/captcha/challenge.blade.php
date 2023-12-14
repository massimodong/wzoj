@switch (config("wzoj.captcha_service"))
  @case ('turnstile')
    <div class="cf-turnstile" data-sitekey="{{config('wzoj.captcha_sitekey')}}"></div>
    @break
  @case ('mews')
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
    @break
@endswitch
