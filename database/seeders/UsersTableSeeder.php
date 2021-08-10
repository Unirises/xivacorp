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
            'dob' => '1980-01-01',
            'address' => "xivahealth's Office",
            'gender' => 0,
        ]);
        DB::table('users')->insert([
            'name' => 'HCP',
            'email' => 'hcp@test.xyz',
            'email_verified_at' => now(),
            'password' => Hash::make('password1234'),
            'created_at' => now(),
            'updated_at' => now(),
            'role' => 1,
            'workspace_id' => 'XVHLTH00',
            'dob' => '1980-01-01',
            'address' => "xivahealth's Office",
            'gender' => 0,
        ]);
        DB::table('hcp_data')->insert([
            'user_id' => 2,
            'type_id' => 3,
            'prc_id' => '1234567890',
            'photo' => 'default.png'
        ]);
        DB::table('users')->insert([
            'name' => 'Employee',
            'email' => 'employee@test.xyz',
            'email_verified_at' => now(),
            'password' => Hash::make('password1234'),
            'created_at' => now(),
            'updated_at' => now(),
            'role' => 4,
            'workspace_id' => 'XVHLTH00',
            'dob' => '1980-01-01',
            'address' => "xivahealth's Office",
            'gender' => 0,
        ]);
        DB::table('users')->insert([
            'name' => 'HR',
            'email' => 'hr@test.xyz',
            'email_verified_at' => now(),
            'password' => Hash::make('password1234'),
            'created_at' => now(),
            'updated_at' => now(),
            'role' => 2,
            'workspace_id' => 'XVHLTH00',
            'dob' => '1980-01-01',
            'address' => "xivahealth's Office",
            'gender' => 0,
        ]);
    }
}
