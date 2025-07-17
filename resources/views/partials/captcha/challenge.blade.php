@section ('head')
@parent
@switch (config("wzoj.captcha_service"))
  @case ('turnstile')
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @break
  @case ('ali')
    <script>
      window.AliyunCaptchaConfig = {
        region: "cn",
        prefix: "{{config('wzoj.captcha_prefix')}}",
      };
    </script>
    <script type="text/javascript" src="https://o.alicdn.com/captcha-frontend/aliyunCaptcha/AliyunCaptcha.js">
    </script>
@endswitch
@endsection

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
  @case ('ali')
    <div id="ali-captcha-element"></div>
    <input style="display:none" id="ali-captcha-input" type="text" name="captcha">
    <script type="text/javascript">
      var captcha;
      window.initAliyunCaptcha({
        SceneId: "{{config('wzoj.captcha_scene_id')}}",
        mode: "embed",
        element: "#ali-captcha-element",
        success: function (captchaVerifyParam) {
          $('#ali-captcha-input').val(captchaVerifyParam);
        },
        fail: function (result) {
          console.error(result);
        },
        getInstance: function (instance) {
          captcha = instance;
        },
        slideStyle: {
          width: 360,
          height: 40,
        },
      });
    </script>
    @break
@endswitch
