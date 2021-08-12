<?php

namespace App\Imports;

use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;

class UsersImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new User([
            'first_name'  => $row[0],
            'email' => $row[1],
            'password' => $row[2],
            'dob' => $row[3] == 'NULL' ? Carbon::now() : Carbon::parse($row[3]),
            'gender' => 0,
            'is_onboarded' => false,
        ]);
    }
}
