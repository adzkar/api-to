<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateDetailedResults extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('detailed_results', function (Blueprint $table) {
            $table->unsignedInteger('id_result')
                  ->nullable()
                  ->change();

            $table->unsignedInteger('id_question')
                  ->nullable()
                  ->change();

            $table->unsignedInteger('id_answer')
                  ->nullable()
                  ->change();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('detailed_results', function (Blueprint $table) {
            //
        });
    }
}
