<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return User::select('lastname','firstname','username','email','phone_number','gender','birthdate',)
        ->where('role','!=','admin')
        ->get();
    }
}
