<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('content');
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('template')->default(1);
            $table->boolean('online')->default(true);
            $table->boolean('inNav')->default(true);
            $table->boolean('isHome')->default(false);
            $table->string('seo_title');
            $table->text('seo_description');
            $table->string('editor');
            $table->integer('updated_at');
            $table->string('author');
            $table->integer('created_at');
            $table->integer('position', false, true);
            $table->integer('real_depth', false, true);
            $table->integer('deleted_at')->nullable();

            $table->foreign('parent_id')->references('id')->on('pages')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pages');
    }
}
