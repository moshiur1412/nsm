<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAwardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('awards', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('receipt');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('subsidy_id')->nullable();
            $table->integer('state')->default(1);
            $table->string('name');
            $table->string('name_kana');
            $table->string('name_alphabet');
            $table->date('birthday');
            $table->string('belong_type_name');
            $table->string('occupation');
            $table->string('zip_code');
            $table->string('address1');
            $table->string('address2')->nullable();
            $table->string('theme');
            $table->unsignedBigInteger('custom_topic_id')->nullable();
            $table->string('attachment_path')->unique()->nullable();
            $table->tinyInteger('is_granted')->default(0);
            $table->tinyInteger('mail_sent')->default(0);
            $table->string('belongs');
            $table->string('major');
            $table->date('offer_year');
            $table->date('subsidy_granted_year');
            $table->date('expiration_date');
            $table->tinyInteger('valid')->default(1);
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
        Schema::dropIfExists('awards');
    }
}
