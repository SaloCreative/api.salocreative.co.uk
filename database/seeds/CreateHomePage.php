<?php

use Illuminate\Database\Seeder;

class CreateHomePage extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('page')->insert([
            'title' => 'Rich',
            'slug' => 'home',
            'content' => ('<p>This the homepage sample content</p>'),
            'isHome' => true,
            'created_at' => time(),
            'updated_at' => time()
        ]);
    }
}
