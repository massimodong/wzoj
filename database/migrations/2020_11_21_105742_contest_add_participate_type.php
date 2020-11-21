<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContestAddParticipateType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('problemsets', function (Blueprint $table) {
            $table->integer('participate_type')->after('contest_end_at');
            $table->integer('contest_duration')->after('participate_type');
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
            $table->dropColumn('contest_duration');
            $table->dropColumn('participate_type');
        });
    }
}
