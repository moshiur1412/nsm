<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJudgesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('judges', function (Blueprint $table) {
       $table->bigIncrements('id');
       $table->string('name');
       $table->integer('role');
       $table->string('email')->nuique();
       $table->string('login_password');
       $table->dateTime('login_expires_at')->nullable(true);
       $table->dateTime('last_login_at')->nullable(true);
       $table->tinyInteger('valid')->default(1);
       $table->string('judgecol');
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
      Schema::dropIfExists('judges');
    }
  }
