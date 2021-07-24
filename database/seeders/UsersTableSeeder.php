<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('companies')->insert([
            'name' => 'Xiva Health',
            'employer' => "Mark Melgar",
            'code' => "XVHLTH00",
            'contact' => "09123456789",
        ]);
        DB::table('users')->insert([
            'name' => 'Mark Melgar',
            'email' => 'corporate@xivahealth.io',
            'email_verified_at' => now(),
            'password' => Hash::make('password1234'),
            'created_at' => now(),
            'updated_at' => now(),
            'role' => 0,
            'workspace_id' => 'XVHLTH00',
        ]);
    }
}
