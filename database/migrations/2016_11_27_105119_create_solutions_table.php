<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSolutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('solutions', function (Blueprint $table) {
            $table->increments('id');
	    $table->integer('user_id');
	    $table->integer('problem_id');
	    $table->integer('problemset_id');

	    $table->integer('language');
	    $table->longText('code');

	    $table->double('code_length');
	    $table->integer('time_used');
	    $table->double('memory_used');
	    $table->integer('status')->default(0);
	    $table->integer('score');

	    $table->longText('ce')->nullable();
	    $table->integer('sim_id')->nullable();

	    $table->timestamp('judged_at');
	    $table->integer('judger_id');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('solutions');
    }
}
