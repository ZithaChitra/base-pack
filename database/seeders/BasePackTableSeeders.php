<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\RolesAndPermsTableSeeder;
use Database\Seeders\SettingThemesTableSeeder;
use Database\Seeders\NavLinksTableSeeder;

class BasePackTableSeeders extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SettingThemesTableSeeder::class,
            RolesAndPermsTableSeeder::class,
            NavLinksTableSeeder::class,
        ]);
    }
}