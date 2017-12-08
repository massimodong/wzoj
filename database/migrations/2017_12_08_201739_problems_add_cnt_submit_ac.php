<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Problem;

class ProblemsAddCntSubmitAc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('problems', function (Blueprint $table) {
		$table->integer('cnt_submit')->after('remark');
		$table->integer('cnt_ac')->after('cnt_submit');
        });
	foreach(Problem::all() as $problem){
		$problem->update_cnt_submit();
		$problem->update_cnt_ac();
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
		$table->dropColumn('cnt_submit');
		$table->dropColumn('cnt_ac');
        });
    }
}
