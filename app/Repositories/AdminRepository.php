<?php

namespace App\Repositories;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminRepository
{
    public function getAdmin($username){
        return Admin::where('email', $username)->first();
    }

    public function getAdminById($id){
        return Admin::where('id', $id)->first();
    }

    public function update_password($password){
        Admin::where('id', session('id'))->update(['password' => Hash::make($password)]);
    }

}
