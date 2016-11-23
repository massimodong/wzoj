<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MakeInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->increments('id');
	    $table->string('description');
	    $table->string('fullname');
	    $table->string('class');
	    $table->string('token');
	    $table->integer('remaining');
	    $table->boolean('private');
            $table->timestamps();
        });
	App\Invitation::create(['description'=>'Default','fullname'=>'','class'=>'','token'=>'default','remaining'=> -1,'private'=> false]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('invitations');
    }
}
