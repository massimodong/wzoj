<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SolutionsAddCntTestcases extends Migration
{
    /**
     * Run the migrations.
     * Happy new year!
     * @return void
     */
    public function up()
    {
        Schema::table('solutions', function (Blueprint $table) {
		$table->integer('cnt_testcases')->after('judger_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('solutions', function (Blueprint $table) {
		$table->dropColumn('cnt_testcases');
        });
    }
}
