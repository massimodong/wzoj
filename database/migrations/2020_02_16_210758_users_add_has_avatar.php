<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersAddHasAvatar extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('stored_description', 255)->change();
            $table->string('new_description', 255)->change();
            $table->boolean('has_avatar')->after('last_login_at');
        });
        Storage::disk('files')->makeDirectory('avatar/default');
        $dir = storage_path('app').'/files/avatar/default/';
        copy(storage_path().'/default-avatar-lg.png', $dir.'/avatar-lg.png');
        copy(storage_path().'/default-avatar-md.png', $dir.'/avatar-md.png');
        copy(storage_path().'/default-avatar-sm.png', $dir.'/avatar-sm.png');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('has_avatar');
        });
    }
}
