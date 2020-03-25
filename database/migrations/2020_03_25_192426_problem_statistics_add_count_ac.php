<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProblemStatisticsAddCountAc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('problem_statistics', function (Blueprint $table) {
            $table->integer('count_ac')->after('score_sum');
        });

        $sts = DB::select("SELECT problemset_id, problem_id, count(*) as count_ac
                           FROM solutions
                           WHERE score = 100
                           GROUP BY problemset_id, problem_id");
        foreach($sts as $st){
          DB::update("UPDATE problem_statistics SET count_ac = ? where problemset_id = ? AND problem_id = ?", [$st->count_ac, $st->problemset_id, $st->problem_id]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('problem_statistics', function (Blueprint $table) {
            $table->dropColumn('count_ac');
        });
    }
}
