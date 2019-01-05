<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tests', function (Blueprint $table) {
            $table->increments('id_test');
            $table->string('title');
            $table->text('information');
            $table->datetime('start');
            $table->datetime('end');

            $table->unsignedInteger('id_com')
                  ->nullable();
            $table->foreign('id_com')
                  ->references('id_com')
                  ->on('committees')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tests');
    }
}
