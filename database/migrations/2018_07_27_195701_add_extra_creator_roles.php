<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraCreatorRoles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
	  App\Role::create(['name' => 'group_creator']);
	  App\Role::create(['name' => 'problem_creator']);
	  App\Role::create(['name' => 'problemset_creator']);
	  App\Role::create(['name' => 'code_viewer']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            //
        });
    }
}
