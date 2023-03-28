<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Corcel\Model\Option;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call('OptionsTableSeeder');
    }
}

class OptionsTableSeeder extends Seeder {
    public function run()
    {
        Option::create(array(
            'option_name' => 'blogname',
            'option_value' => 'Go@',
            'autoload' => 'yes'
        ));

        Option::create(array(
            'option_name' => 'blogdescription',
            'option_value' => 'Det er dyrt at handle i byen. Få en anden til det.',
            'autoload' => 'yes'
        ));
    }
}