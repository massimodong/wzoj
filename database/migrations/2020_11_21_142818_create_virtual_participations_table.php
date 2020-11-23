<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVirtualParticipationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('virtual_participations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('problemset_id');
            $table->timestamp('contest_start_at');
            $table->timestamp('contest_end_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('virtual_participations');
    }
}
