<?php

use Illuminate\Database\Seeder;

class CreateModules extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('modules')->insert([
            ['module' => 'BLOG', 'label' => 'Blog', 'required' => 0],
            ['module' => 'CONTACT', 'label' => 'Contacts', 'required' => 0],
            ['module' => 'DASHBOARD', 'label' => 'Dashboard', 'required' => 1],
            ['module' => 'FORM', 'label' => 'Forms', 'required' => 0],
            ['module' => 'LEAD', 'label' => 'Leads', 'required' => 0],
            ['module' => 'MEDIA', 'label' => 'Asset Library', 'required' => 0],
            ['module' => 'MESSAGE', 'label' => 'Messages', 'required' => 0],
            ['module' => 'PAGE', 'label' => 'Pages', 'required' => 0],
            ['module' => 'PRODUCT', 'label' => 'Products', 'required' => 0],
            ['module' => 'SETTING', 'label' => 'Settings', 'required' => 1],
            ['module' => 'TASK', 'label' => 'Tasks', 'required' => 0]
        ]);
    }
}
