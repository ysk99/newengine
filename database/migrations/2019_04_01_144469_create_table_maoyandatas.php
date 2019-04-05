<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMaoyandatas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maoyandatas', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title');
             $table->text('rating');
             $table->integer('rank');
             $table->text('img');
             $table->string('year');
             $table->longText('quto');
             $table->longText('casts');
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
        Schema::dropIfExists('maoyandatas');
    }
}
