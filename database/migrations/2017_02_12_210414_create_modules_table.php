<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

class CreateModulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->increments('id');
            $table->string('module')->unique();
            $table->string('label')->unique();
            $table->boolean('active')->default(true);
            $table->boolean('available')->default(true);
            $table->integer('userLevel');
            $table->boolean('required')->default(false);
        });

        //now the data migration
        Artisan::call('db:seed', [
            '--class' => CreateModules::class,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('modules');
    }
}
