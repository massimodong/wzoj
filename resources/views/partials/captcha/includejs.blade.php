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
