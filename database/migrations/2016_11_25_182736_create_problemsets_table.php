<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProblemsetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('problemsets', function (Blueprint $table) {
            $table->increments('id');
	    $table->string('name');
	    $table->string('type');
	    $table->boolean('public');
	    $table->longText('description');
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
        Schema::drop('problemsets');
    }
}
