<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('items')->insert([
            'name' => 'Item 1',
            'photo' => 'default.png',
            'viewable_as' => UserRole::HCP,
            'price' => 30,
        ]);
        DB::table('items')->insert([
            'name' => 'Item 2',
            'photo' => 'default.png',
            'viewable_as' => UserRole::HR,
            'price' => 50,
        ]);
        DB::table('items')->insert([
            'name' => 'Item 3',
            'photo' => 'default.png',
            'viewable_as' => UserRole::Clinic,
            'price' => 70,
        ]);
        DB::table('items')->insert([
            'name' => 'Item 4',
            'photo' => 'default.png',
            'viewable_as' => UserRole::Employee,
            'price' => 100,
        ]);
    }
}
