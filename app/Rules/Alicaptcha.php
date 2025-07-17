<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

use AlibabaCloud\SDK\Captcha\V20230305\Captcha;
use AlibabaCloud\Credentials\Credential;
use \Exception;
use AlibabaCloud\Tea\Exception\TeaError;
use AlibabaCloud\Tea\Utils\Utils;

use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\SDK\Captcha\V20230305\Models\VerifyIntelligentCaptchaRequest;

class Alicaptcha implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public static function createClient(){
      $credential = new Credential();
      $config = new Config([
          "accessKeyId" => config("wzoj.alibaba_cloud_access_key_id"),
          "accessKeySecret" => config("wzoj.alibaba_cloud_access_key_secret")
      ]);
      $config->endpoint = "captcha.cn-shanghai.aliyuncs.com";
      return new Captcha($config);
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
      $client = self::createClient();
      $verifyIntelligentCaptchaRequest = new VerifyIntelligentCaptchaRequest([
          "sceneId" => config('wzoj.captcha_scene_id'),
          "captchaVerifyParam" => $value
      ]);
      try {
        return $client->verifyIntelligentCaptcha($verifyIntelligentCaptchaRequest)->body->result->verifyResult;
      }
      catch (Exception $error) {
        throw $error;
      }
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
