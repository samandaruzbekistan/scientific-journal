<?php

namespace App\Repositories;

use App\Models\EditorialsTeam;

class EditorialsTeamRepository
{
    public function getAll(){
        return EditorialsTeam::latest()->get();
    }

    public function getByTypeId($id){
        return EditorialsTeam::where('article_type_id', $id)->first();
    }

    public function getById($id){
        return EditorialsTeam::find($id);
    }

    public function create($data){
        return EditorialsTeam::create($data);
    }

//    public function update($data, $id){
//        return EditorialsTeam::find($id)->update($data);
//    }
//
//    public function delete($id){
//        return EditorialsTeam::destroy($id);
//    }
}
