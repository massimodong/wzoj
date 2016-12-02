<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestcasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('testcases', function (Blueprint $table) {
            $table->increments('id');
	    $table->integer('solution_id');
	    $table->string('filename');

	    $table->integer('time_used');
	    $table->double('memory_used');
	    $table->string('verdict');
	    $table->integer('score');

	    $table->longText('checklog');

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
        Schema::drop('testcases');
    }
}
