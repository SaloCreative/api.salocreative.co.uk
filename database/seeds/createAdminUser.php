<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class CreateAdminUser extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('user')->insert([
            'name' => 'Rich',
            'email' => 'admin@salocreative.co.uk',
            'password' => bcrypt('password'),
        ]);
    }
}
