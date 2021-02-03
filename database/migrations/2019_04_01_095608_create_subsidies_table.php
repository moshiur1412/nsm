<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSubsidiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

    	Schema::create('subsidies', function (Blueprint $table) {
    		$table->bigIncrements('id')->unsigned();
    		$table->string('receipt')->unique();
    		$table->unsignedBigInteger('user_id')->nullable();
    		$table->integer('state')->default(1);
    		$table->string('name');
    		$table->string('name_kana');
    		$table->string('name_alphabet');
    		$table->date('birthday');
    		$table->string('belong_type_name');
    		$table->string('major');
    		$table->string('belongs');
    		$table->string('occupation');
    		$table->string('zip_code');
    		$table->string('address1');
    		$table->string('address2')->nullable();
    		$table->string('theme');
    		$table->unsignedBigInteger('custom_topic_id')->nullable();
    		$table->text('topic');
    		$table->string('application_path')->unique();
    		$table->string('attachment_path')->unique();
    		$table->string('reference_path')->unique();
    		$table->string('merged_path')->nullable();
            $table->tinyInteger('primary_granted')->default(0);
            $table->tinyInteger('is_granted')->default(0);
            $table->tinyInteger('mail_sent')->default(0);
            $table->date('offer_year')->nullable();
            $table->date('expiration_date')->nullable();
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
    	Schema::dropIfExists('subsidies');
    }
}
