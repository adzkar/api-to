<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->increments('id_participant');
            $table->string('first_name',20);
            $table->string('last_name',20);
            $table->string('username',20)
                  ->unique();
            $table->string('password');
            $table->string('school',100);

            $table->unsignedInteger('id_ver')
                  ->nullable();
            $table->foreign('id_ver')
                  ->references('id_ver')
                  ->on('verification')
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
        Schema::dropIfExists('participants');
    }
}
