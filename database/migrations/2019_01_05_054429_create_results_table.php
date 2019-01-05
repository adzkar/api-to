<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('results', function (Blueprint $table) {
            $table->increments('id_result');
            $table->integer('score');

            $table->unsignedInteger('id_participant');
            $table->foreign('id_participant')
                  ->references('id_participant')
                  ->on('participants')
                  ->onDelete('cascade');

            $table->unsignedInteger('id_test');
            $table->foreign('id_test')
                  ->references('id_test')
                  ->on('tests')
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
        Schema::dropIfExists('results');
    }
}
