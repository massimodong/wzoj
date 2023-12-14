<?php

use App\Rules\Turnstile;

function captchaGetRequestName(){
  switch(config("wzoj.captcha_service")){
    case "turnstile":
      return "cf-turnstile-response";
    case "mews":
      return "captcha";
  }
}

function captchaGetValidation(){
  switch(config("wzoj.captcha_service")){
    case "turnstile":
      return new Turnstile;
    case "mews":
      return "required|captcha";
  }
}
