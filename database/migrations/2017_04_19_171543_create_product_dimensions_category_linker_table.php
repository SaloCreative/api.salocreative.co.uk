<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductDimensionsCategoryLinkerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dimension_field_product_category', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_category_id')->unsigned()->nullable();
            $table->integer('dimension_field_id')->unsigned()->nullable();
        });

        Schema::table('dimension_field_product_category', function($table) {
            $table->foreign('product_category_id')->references('id')->on('product_categories')->onDelete('cascade');
            $table->foreign('dimension_field_id')->references('id')->on('dimension_fields')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dimension_field_product_category');
    }
}
