<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\UserLog;

class RemovePasswordFromFailedLoginLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      UserLog::where('action_name', 'failed_login')->chunkById(1000, function($userlogs){
        foreach ($userlogs as $userlog){
          $userlog->action_payload = [
            "name" => $userlog->action_payload["name"],
          ];
          $userlog->save();
        }
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
