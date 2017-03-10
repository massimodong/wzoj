<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersAddDescriptionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
		$table->longText('description')->after('class_lock');
		$table->integer('cnt_ac')->after('description');
        });
	DB::update('UPDATE `users` SET `cnt_ac` = (SELECT count(DISTINCT `problem_id`) FROM `solutions` WHERE `users`.`id` = `solutions`.`user_id` AND `solutions`.`score` >= 100)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
		$table->dropColumn('description');
		$table->dropColumn('cnt_ac');
        });
    }
}
