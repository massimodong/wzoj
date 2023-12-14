<?php

namespace App\Rules;

use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Validation\Rule;

class Turnstile implements Rule
{
    public const VERIFY_URL = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
      $captcha_resp = Http::asForm()->post(self::VERIFY_URL, [
          'response' => $value,
          'secret' => config('wzoj.captcha_secretkey'),
          'remoteip' => getRemoteAddr()["ip"],
      ])->json();

      return (bool) collect($captcha_resp)->get('success');
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('wzoj.turnstile_error');
    }
}
