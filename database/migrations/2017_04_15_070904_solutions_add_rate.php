<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SolutionsAddRate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('solutions', function (Blueprint $table) {
		$table->bigInteger('rate')->after('cnt_testcases');
        });
	DB::table('solutions')
		->where('status', 4)
		->update(['rate' => DB::raw('score * 10000000000000000 - time_used * 100000000000 - memory_used -code_length')]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('solutions', function (Blueprint $table) {
		$table->dropColumn('rate');
        });
    }
}
