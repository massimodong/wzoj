<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Solution;

class SolutionsAddTestcases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('solutions', function (Blueprint $table) {
		$table->text('testcases')->after('ce');
        });
	foreach(Solution::all() as $solution){
		$testcases = DB::select('SELECT filename, time_used, memory_used, verdict, score, checklog FROM testcases WHERE solution_id = ?', [$solution->id]);
		$arr = Array();
		foreach($testcases as $testcase){
			array_push($arr, $testcase);
		}
		$solution->testcases = $arr;
		$solution->save();
	}
	Schema::drop('testcases');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('solutions', function (Blueprint $table) {
		$table->dropColumn('testcases');
        });
    }
}
