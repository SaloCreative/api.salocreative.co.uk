<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

class CreateBlogCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->boolean('online')->default(true);
            $table->boolean('deletable')->default(true);
            $table->string('seo_title');
            $table->text('seo_description');
            $table->integer('updated_at');
            $table->integer('created_at');

        });


        //now the data migration
        Artisan::call('db:seed', [
            '--class' => CreateBlogCategory::class,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_categories');
    }
}
