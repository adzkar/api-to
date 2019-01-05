<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailedResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detailed_results', function (Blueprint $table) {
            $table->increments('id_dr');
            $table->boolean('status');

            $table->unsignedInteger('id_result');
            $table->foreign('id_result')
                  ->references('id_result')
                  ->on('results')
                  ->onDelete('cascade');

            $table->unsignedInteger('id_question');
            $table->foreign('id_question')
                  ->references('id_question')
                  ->on('questions')
                  ->onDelete('cascade');

            $table->unsignedInteger('id_answer');
            $table->foreign('id_answer')
                  ->references('id_answer')
                  ->on('answers')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detailed_results');
    }
}
