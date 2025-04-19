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
    $this->validate($request, [
        'task' => 'required',
    ]);

    $target_phone = 0;

    if($request->task === 'link-phone'){
      $this->validate($request, [
          'phone' => 'required|digits:11',
      ]);
      $target_phone = $request->phone;
    }else{
      if(is_null(Auth::user()->phone_number) || empty(Auth::user()->phone_number)){
        return response()->json(["ok" => false, "msg" => "Error!"], 400);
      }
      $target_phone = Auth::user()->phone_number;
    }

    $params = [];
    switch($request->task){
      case 'link-phone':
        $params["phone"] = $target_phone;
        break;
      default:
        return response()->json(["ok" => false, "msg" => "Error!"], 400);
    }

    logAction('send_sms_request', array_merge(["action_code" => $request->task], $params), LOG_MODERATE);

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
        "phoneNumbers" => $target_phone,
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
      'params' => $params,
      'targets' => [
        "phone" => $target_phone,
      ],
    ]);

    logAction('send_sms_success', array_merge(["action_code" => $request->task], $params), LOG_MODERATE);

    return response()->json(["ok" => true]);
  }
}
