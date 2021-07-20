<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Problem;

class ProblemsReformat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('problems', function (Blueprint $table) {
            //
        });
        $problems = Problem::all();
        foreach($problems as $problem){
          $problem->description = Purifier::clean($problem->description);
          $problem->inputformat = Purifier::clean($problem->inputformat);
          $problem->outputformat = Purifier::clean($problem->outputformat);
          $problem->hint = Purifier::clean($problem->hint);
          $problem->tutorial = Purifier::clean($problem->tutorial);
          $problem->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('problems', function (Blueprint $table) {
            //
        });
    }
}
