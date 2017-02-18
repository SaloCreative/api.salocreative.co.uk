<?php

use Illuminate\Database\Seeder;

class CreateBlogCategory extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('blog_categories')->insert([
            'title' => 'Default Category',
            'slug' => 'default-category',
            'created_at' => time(),
            'updated_at' => time(),
            'deletable' => false
        ]);
    }
}
