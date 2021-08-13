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
            'first_name'  => $this->split_name($row[0])[0],
            'last_name'  => $this->split_name($row[0])[1],
            'email' => $row[1],
            'password' => $row[2],
            'dob' => $row[3] == 'NULL' ? Carbon::now() : Carbon::parse($row[3]),
            'gender' => 0,
            'is_onboarded' => false,
        ]);
    }

    private function split_name($name) {
        $name = trim($name);
        $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
        $first_name = trim( preg_replace('#'.preg_quote($last_name,'#').'#', '', $name ) );
        return array($first_name, $last_name);
    }
}
