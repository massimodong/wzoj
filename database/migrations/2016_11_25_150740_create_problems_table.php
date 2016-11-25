<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProblemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('problems', function (Blueprint $table) {
            $table->increments('id');
	    $table->string('name');

	    $table->integer('type');
	    $table->boolean('spj');

	    $table->longText('description');
	    $table->longText('inputformat');
	    $table->longText('outputformat');
	    $table->longText('sampleinput');
	    $table->longText('sampleoutput');
	    $table->longText('hint');
	    $table->string('source');

	    $table->integer('timelimit');
	    $table->double('memorylimit');

            $table->timestamps();
	    $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('problems');
    }
}
