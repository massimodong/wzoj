<?php

use App\Rules\Turnstile;
use App\Rules\Alicaptcha;

function captchaGetRequestName(){
  switch(config("wzoj.captcha_service")){
    case "turnstile":
      return "cf-turnstile-response";
    case "mews":
      return "captcha";
    case "ali":
      return "captcha";
  }
}

function captchaGetValidation(){
  switch(config("wzoj.captcha_service")){
    case "turnstile":
      return ['required', new Turnstile];
    case "mews":
      return "required|captcha";
    case "ali":
      return ['required', new Alicaptcha];
  }
}
