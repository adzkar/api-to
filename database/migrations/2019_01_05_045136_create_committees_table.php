<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommitteesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('committees', function (Blueprint $table) {
            $table->increments('id_com');
            $table->string('name',20);
            $table->string('username',20)
                  ->unique();
            $table->string('password');

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
        Schema::dropIfExists('committees');
    }
}
