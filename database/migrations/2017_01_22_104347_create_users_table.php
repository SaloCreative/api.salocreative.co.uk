<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('surname');
            $table->string('company');
            $table->string('email')->unique();
            $table->string('phone');
            $table->string('image');
            $table->string('password')->nullable(false);
            $table->boolean('suspended')->default(false);
            $table->integer('updated_at');
            $table->integer('created_at');
            $table->integer('deleted_at')->nullable();
        });

        //now the data migration
        Artisan::call('db:seed', [
            '--class' => CreateAdminUser::class,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
