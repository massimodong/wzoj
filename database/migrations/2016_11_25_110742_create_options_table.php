<?php

use App\Option;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('options', function (Blueprint $table) {
		$table->increments('id');
		$table->string('name')->index();
		$table->string('value');
		$table->timestamps();
		});
	    Option::create(['name'=>'site_name' , 'value'=>'WZMS ONLINE JUDGE']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('options');
    }
}
