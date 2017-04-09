<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatMediaLinkerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_imageables', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('file_id')->unsigned()->nullable();
            $table->integer('imageable_id');
            $table->string('imageable_type');
            $table->string('zone');
        });

        Schema::table('media_imageables', function($table) {
            $table->foreign('file_id')->references('id')->on('media')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('media_imageables');
    }
}
