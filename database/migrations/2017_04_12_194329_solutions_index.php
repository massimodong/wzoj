<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SolutionsIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('solutions', function (Blueprint $table) {
		$table->index('user_id');
		$table->index('problem_id');
		$table->index('problemset_id');
		$table->index('status');
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
		$table->dropIndex(['user_id']);
		$table->dropIndex(['problem_id']);
		$table->dropIndex(['problemset_id']);
		$table->dropIndex(['status']);
        });
    }
}
