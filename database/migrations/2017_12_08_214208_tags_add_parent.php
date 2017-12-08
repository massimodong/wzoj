<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TagsAddParent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('problem_tags', function (Blueprint $table) {
		$table->integer('parent_id')->after('reference_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('problem_tags', function (Blueprint $table) {
		$table->dropColumn('parent_id');
        });
    }
}
