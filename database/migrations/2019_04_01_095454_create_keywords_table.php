<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKeywordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keywords', function (Blueprint $table) {
          $table->bigIncrements('id');
          $table->unsignedBigInteger('topic_id')->nullable();
          $table->string('prefix')->unique();
          $table->string('name');
          $table->timestamps();

          $table->foreign('topic_id')->references('id')->on('topics')->onDelete('cascade');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('keywords');
    }
}
