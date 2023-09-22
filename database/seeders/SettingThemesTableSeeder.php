<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use BasePack\Models\SettingTheme;

class SettingThemesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            $first = SettingTheme::where('name', 'default')->get();
            if(count($first) < 1){
                SettingTheme::firstOrCreate(
                    ['name' => 'default'],
                    [
                        'name' => 'default',
                        'active' => true
                    ],
                );
            }
        } catch (\Exception $e) {
            \Log::error('Could not add default theme to database: ' . $e->getMessage());
        }
    }
}
