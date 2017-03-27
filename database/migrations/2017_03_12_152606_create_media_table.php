<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('folder');
            $table->string('file_size');
            $table->string('type');
            $table->string('extension');
            $table->string('mime');
            $table->integer('dimension_width');
            $table->integer('dimension_height');
            $table->string('alt_tag');
            $table->text('description');
            $table->integer('updated_at');
            $table->integer('created_at');
            $table->integer('deleted_at')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media');
    }
}
