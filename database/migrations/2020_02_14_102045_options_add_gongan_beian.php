<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use App\Option;

class OptionsAddGonganBeian extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('options', function (Blueprint $table) {
            Option::create(['name'=>'gongan_code' , 'value'=>'']);
            Option::create(['name'=>'gongan_name' , 'value'=>'']);
            Option::create(['name'=>'gongan_icon_url' , 'value'=>'']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('options', function (Blueprint $table) {
            //
        });
    }
}
