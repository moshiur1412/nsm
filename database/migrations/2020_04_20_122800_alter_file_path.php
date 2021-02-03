<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterFilePath extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('subsidies', function (Blueprint $table) {
            $table->dropUnique('subsidies_application_path_unique');
            $table->dropUnique('subsidies_attachment_path_unique');
            $table->dropUnique('subsidies_reference_path_unique');
       });
        Schema::table('awards', function (Blueprint $table) {
            $table->dropUnique('awards_attachment_path_unique');
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
