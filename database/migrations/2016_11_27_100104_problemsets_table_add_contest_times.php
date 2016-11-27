<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProblemsetsTableAddContestTimes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('problemsets', function (Blueprint $table) {
		$table->timestamp('contest_start_at')->after('description');
		$table->timestamp('contest_end_at')->after('contest_start_at');
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
		$table->dropColumn('contest_start_at');
		$table->dropColumn('contest_end_at');
        });
    }
}
