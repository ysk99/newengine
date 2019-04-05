<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableHotsearchs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotsearchs', function (Blueprint $table) {
            $table->increments('id');
            $table->text('name');
             $table->integer('uid');
             // $table->string('website');
             // $table->longText('others');
             // $table->integer('recommend');
             // $table->string('leixing');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('otsearchs');
    }
}
