<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{

    public function getAll()
    {
        return User::all();
    }

    public function getById($id)
    {
        return User::find($id);
    }

    public function getByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function getByPhone($phone)
    {
        return User::where('phone', $phone)->first();
    }

    public function create(array $data): User
    {
        $user = User::create($data);

        $user->assignRole($data['role']);

        return $user;
    }

    public function update($id, array $data)
    {
        $user = User::findOrFail($id);
        $user->update($data);
        return $user;
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return true;
    }
}
