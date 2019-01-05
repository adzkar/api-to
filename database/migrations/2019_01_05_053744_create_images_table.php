<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->increments('id_image');
            $table->string('mime')
                  ->nullable();
            $table->string('file_name')
                  ->nullable();
            $table->string('original_file_name')
                  ->nullable();

            $table->unsignedInteger('id_question');
            $table->foreign('id_question')
                  ->references('id_question')
                  ->on('questions')
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
        Schema::dropIfExists('images');
    }
}
