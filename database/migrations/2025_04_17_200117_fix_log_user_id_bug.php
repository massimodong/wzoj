<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\UserLog;

class FixLogUserIdBug extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      UserLog::whereNull('user_id')->chunkById(100, function($userlogs){
        foreach ($userlogs as $userlog){
          switch($userlog->action_name){
            case "registered":
              if(array_key_exists("id", $userlog->action_payload)){
                $userlog->user_id = $userlog->action_payload["id"];
                $userlog->save();
              }
              break;
            case "failed_login":
              $user = \App\User::where('name', $userlog->action_payload["name"])->first();
              if(!is_null($user)){
                $userlog->user_id = $user->id;
                $userlog->save();
              }
              break;
            case "password_reset":
              if(array_key_exists("id", $userlog->action_payload)){
                $userlog->user_id = $userlog->action_payload["id"];
                $userlog->save();
              }
              break;
          }
        }
      });
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
