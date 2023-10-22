<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ProblemsetAddShowCodeTemplate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('problemsets', function (Blueprint $table) {
            $table->boolean('show_code_template')->after('show_tutorial');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('problemsets', function (Blueprint $table) {
            $table->dropColumn('show_code_template');
        });
    }
}
