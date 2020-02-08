<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProblemStatistics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('problem_statistics', function (Blueprint $table) {
            $table->integer('problemset_id');
            $table->integer('problem_id');
            $table->integer('count');
            $table->integer('score_sum');

            $table->primary(['problemset_id', 'problem_id']);
        });

        DB::insert("INSERT INTO problem_statistics
                      SELECT problemset_id, problem_id, count(*), sum(score)
                      FROM solutions
                      GROUP BY problemset_id, problem_id");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('problem_statistics');
    }
}
