<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductCategoryClosuresTable extends Migration
{
    public function up()
    {
        Schema::create('product_category_closure', function(Blueprint $table)
        {
            $table->increments('closure_id');

            $table->integer('ancestor', false, true);
            $table->integer('descendant', false, true);
            $table->integer('depth', false, true);

            $table->foreign('ancestor')->references('id')->on('product_categories')->onDelete('cascade');
            $table->foreign('descendant')->references('id')->on('product_categories')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('product_category_closure', function(Blueprint $table)
        {
            Schema::dropIfExists('product_category_closure');
        });
    }
}
