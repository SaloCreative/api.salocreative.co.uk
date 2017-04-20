<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductDimensionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dimensions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('dimension');
            $table->integer('field')->unsigned()->nullable();
            $table->integer('updated_at');
            $table->integer('created_at');
        });

        Schema::table('dimensions', function($table) {
            $table->foreign('field')->references('id')->on('dimension_fields')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dimensions');
    }
}
