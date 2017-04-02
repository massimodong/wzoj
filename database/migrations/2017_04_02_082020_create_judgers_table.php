<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJudgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('judgers', function (Blueprint $table) {
            $table->increments('id');
	    $table->string('name');
	    $table->string('token');
            $table->timestamps();
        });
	DB::delete('DELETE FROM roles WHERE id = 2');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('judgers');
    }
}
