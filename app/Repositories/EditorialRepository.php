<?php

namespace App\Repositories;

use App\Models\Editorial;

class EditorialRepository
{
    public function getAll(){
        return Editorial::latest()->get();
    }

    public function getById($id){
        return Editorial::find($id);
    }

    public function getByEmail($email){
        return Editorial::where('email', $email)->first();
    }

    public function create($data){
        $editorial = Editorial::create($data);
        $editorial->assignRole($data['role']);
        return $editorial;
    }

    public function update($data, $id){
        return Editorial::find($id)->update($data);
    }

    public function delete($id){
        return Editorial::destroy($id);
    }

    public function search($name){
        return Editorial::where('name', 'like', '%'.$name.'%')->get();
    }
}
