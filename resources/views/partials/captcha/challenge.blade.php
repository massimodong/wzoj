@switch (config("wzoj.captcha_service"))
  @case ('turnstile')
    <div class="cf-turnstile" data-sitekey="{{config('wzoj.captcha_sitekey')}}"></div>
    @break
@endswitch
