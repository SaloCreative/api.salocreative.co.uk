<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('sku')->unique();
            $table->double('price', 15, 2);
            $table->integer('featured_image')->nullable();
            $table->text('content');
            $table->integer('category_id')->unsigned()->nullable();
            $table->boolean('online')->default(false);
            $table->boolean('inStock')->default(true);
            $table->string('seo_title');
            $table->text('seo_description');
            $table->string('editor');
            $table->integer('updated_at');
            $table->string('author')->default(1);
            $table->integer('created_at');
            $table->integer('deleted_at')->nullable();

        });

        Schema::table('products', function($table) {
            $table->foreign('category_id')->references('id')->on('product_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
