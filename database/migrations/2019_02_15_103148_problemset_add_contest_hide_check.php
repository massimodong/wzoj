<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProblemsetAddContestHideCheck extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('problemsets', function (Blueprint $table) {
		$table->boolean('contest_hide_solutions')->after('show_problem_tags');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('problemsets', function (Blueprint $table) {
		$table->dropColumn('contest_hide_solutions');
        });
    }
}
