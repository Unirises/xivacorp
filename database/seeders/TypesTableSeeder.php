<?php
namespace Database\Seeders;

use App\Enums\TypeIdent;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('types')->insert([
            'name' => 'Nurse',
            'type' => TypeIdent::HCP,
        ]);
        DB::table('types')->insert([
            'name' => 'Medical Technologist',
            'type' => TypeIdent::HCP,
        ]);
        DB::table('types')->insert([
            'name' => 'Doctor',
            'type' => TypeIdent::HCP,
        ]);
        DB::table('types')->insert([
            'name' => 'COVID-19 Nasal Antigen Test',
            'type' => TypeIdent::Tests,
        ]);
        DB::table('types')->insert([
            'name' => 'COVID-19 Nasopharyngeal Antigen Test',
            'type' => TypeIdent::Tests,
        ]);
        DB::table('types')->insert([
            'name' => 'Testing Services',
            'type' => TypeIdent::Services,
        ]);
        DB::table('types')->insert([
            'name' => 'Vaccination Services',
            'type' => TypeIdent::Services,
        ]);
    }
}
