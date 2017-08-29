<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Sidebar;

class SidebarAddIndex extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sidebars', function (Blueprint $table) {
		$table->integer('index')->after('url');
        });
	Sidebar::where('name', 'FAQ')->update(['index' => 6]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sidebars', function (Blueprint $table) {
		$table->dropColumn('index');
        });
    }
}
