<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProblemAddSubtasks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('problems', function (Blueprint $table) {
		$table->boolean('use_subtasks')->after('spj');
		$table->longText('subtasks')->after('use_subtasks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('problems', function (Blueprint $table) {
		$table->dropColumn('use_subtasks');
		$table->dropColumn('subtasks');
        });
    }
}
