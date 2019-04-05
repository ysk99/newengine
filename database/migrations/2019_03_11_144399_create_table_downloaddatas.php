<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDownloaddatas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('downloaddatas', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title');
             $table->text('href');
             $table->string('website');
             $table->longText('others');
             $table->integer('recommend');
             $table->string('leixing');
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
        Schema::dropIfExists('downloaddatas');
    }
}
