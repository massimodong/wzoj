<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\VerificationCode;

use Illuminate\Support\Facades\Redis;

use AlibabaCloud\SDK\Dysmsapi\V20170525\Dysmsapi;
use \Exception;
use AlibabaCloud\Tea\Exception\TeaError;
use AlibabaCloud\Tea\Utils\Utils;

use Darabonba\OpenApi\Models\Config;
use AlibabaCloud\SDK\Dysmsapi\V20170525\Models\SendSmsRequest;

class VerificationCodeController extends Controller
{
  const SMS_TTL = 10; // 10 minutes
  public static function createAlibabaClient(){
      $config = new Config([
          "accessKeyId" => getenv("ALIBABA_CLOUD_ACCESS_KEY_ID"),
          "accessKeySecret" => getenv("ALIBABA_CLOUD_ACCESS_KEY_SECRET")
      ]);
      $config->endpoint = "dysmsapi.aliyuncs.com";
      return new Dysmsapi($config);
  }

  public function send(Request $request){
    if(is_null(Auth::user()->phone_number) || empty(Auth::user()->phone_number)){
      return response()->json(["ok" => false], 400);
    }

    $this->validate($request, [
        'task' => 'required',
    ]);

    //we use redit to implement a `send lock` for each user, which automatically expires in 60 seconds
    $res = Redis::set('wzoj.sms_send_lock.'.Auth::user()->id, '1', 'ex', 60, 'nx');
    if($res != 'OK')
      return response()->json(["ok" => false, 'msg' => trans('wzoj.submit_too_frequent')], 422);


    //TODO: anti bot
    Auth::user()->isbot(10);

    $code = sprintf("%06d", random_int(0,999999));


    // Alibaba sms
    $sms_templates = json_decode(ojoption("sms_templates"), true);
    $template_name = $sms_templates[$request->task];

    $client = self::createAlibabaClient();
    $sendSmsRequest = new SendSmsRequest([
        "phoneNumbers" => Auth::user()->phone_number,
        "signName" => getenv("ALIBABA_SMS_SIGNNAME"),
        "templateCode" => $template_name,
        "templateParam" => "{\"code\":\"".$code."\",\"time\":\"".strval(self::SMS_TTL)."\"}",
    ]);
    try {
        $res = $client->sendSms($sendSmsRequest)->toArray()["body"];
        if($res["Code"] !== "OK") return response()->json(["ok" => false, 'msg' => $res["Message"]], 500);
    }
    catch (Exception $error) {
        if (!($error instanceof TeaError)) {
            $error = new TeaError([], $error->getMessage(), $error->getCode(), $error);
        }
        return response()->json(["ok" => false, 'msg' => trans('wzoj.send_sms_failed')], 500);
    }
    // Alibaba sms end

    Auth::user()->verification_codes()->create([
      'code' => $code,
      'task' => $request->task,
      'verified' => false,
    ]);

    return response()->json(["ok" => true]);
  }
}
