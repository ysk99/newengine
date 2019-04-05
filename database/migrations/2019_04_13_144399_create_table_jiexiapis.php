<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableJiexiapis extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jiexiapis', function (Blueprint $table) {
            $table->increments('id');
            // $table->text('title');
             $table->string('url');
             $table->string('website');
             $table->longText('others');
             $table->integer('recommend');
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
        Schema::dropIfExists('jiexiapis');
    }
}
