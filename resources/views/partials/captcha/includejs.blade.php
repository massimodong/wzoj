@switch (config("wzoj.captcha_service"))
  @case ('turnstile')
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
    @break
@endswitch
