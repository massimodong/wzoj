<?php

return [
  'socket_io_server' => env('SOCKET_IO_SERVER', 'localhost'),
  'socket_io_port' => env('SOCKET_IO_PORT', 443),
  'use_proxy' => env('USE_PROXY', false),
  'captcha_service' => env('CAPTCHA_SERVICE', 'mews'),
  'captcha_sitekey' => env('CAPTCHA_SITEKEY', ''),
  'captcha_secretkey' => env('CAPTCHA_SECRETKEY', ''),
  'http_judger_on' => env('HTTP_JUDGER_ON', false),
  'http_judger_url' => env('HTTP_JUDGER_URL', '')
];
