<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            'surname' => 'Comber',
            'company' => 'Salo Creative',
            'phone' => '07840 309664',
            'email' => 'admin@salocreative.co.uk',
            'password' => Hash::make('password'),
            'created_at' => time(),
            'updated_at' => time()
        ]);
    }
}
