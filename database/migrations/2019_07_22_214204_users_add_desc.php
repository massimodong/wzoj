<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersAddDesc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('description_changed_at')->after('description');
            $table->longText('new_description')->after('description_changed_at');
            $table->renameColumn('description', 'stored_description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('stored_description', 'description');
            $table->dropColumn('new_description');
            $table->dropColumn('description_changed_at');
        });
    }
}
