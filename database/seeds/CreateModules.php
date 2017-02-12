<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            ['module' => 'BLOG', 'label' => 'Blog'],
            ['module' => 'CONTACT', 'label' => 'Contacts'],
            ['module' => 'DASHBOARD', 'label' => 'Dashboard'],
            ['module' => 'FORM', 'label' => 'Forms'],
            ['module' => 'LEAD', 'label' => 'Leads'],
            ['module' => 'MEDIA', 'label' => 'Asset Library'],
            ['module' => 'MESSAGE', 'label' => 'Messages'],
            ['module' => 'PAGE', 'label' => 'Pages'],
            ['module' => 'PRODUCT', 'label' => 'Products'],
            ['module' => 'SETTING', 'label' => 'Settings'],
            ['module' => 'TASK', 'label' => 'Tasks']
        ]);
    }
}
