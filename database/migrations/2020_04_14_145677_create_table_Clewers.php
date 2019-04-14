<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableClewers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clewers', function (Blueprint $table) {
            $table->increments('id');
            $table->text('website');
             $table->string('url');
             $table->longText('leixing');
             $table->integer('recommend');
             $table->string('schedule1');
             $table->string('schedule2');
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
        Schema::dropIfExists('clewers');
    }
}
