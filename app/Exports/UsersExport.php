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
        return User::select('name','prenom','username','email','phone_number','region','genre','naissance','type')
        ->where('role','!=','admin')
        ->get();
    }
}
