<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterReceiptNo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subsidies', function (Blueprint $table) {
            $table->dropUnique('subsidies_receipt_unique');
       });
        Schema::table('subsidies', function (Blueprint $table) {
            $table->integer('receipt')->change();
       });
        Schema::table('awards', function (Blueprint $table) {
            $table->integer('receipt')->change();
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
