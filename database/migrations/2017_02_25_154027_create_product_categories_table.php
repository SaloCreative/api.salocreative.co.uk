<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('product_categories', function(Blueprint $table)
        {
            $table->increments('id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('position', false, true);
            $table->integer('real_depth', false, true);
            $table->boolean('online')->default(true);
            $table->string('seo_title');
            $table->text('seo_description');
            $table->integer('updated_at');
            $table->integer('created_at');

            $table->foreign('parent_id')->references('id')->on('product_categories')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('product_categories', function(Blueprint $table)
        {
            Schema::dropIfExists('product_categories');
        });
    }
}
