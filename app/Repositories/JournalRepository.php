<?php

namespace App\Repositories;

use App\Models\Journal;

class JournalRepository
{
    public function getJournal($id){
        return Journal::where('id', $id)->first();
    }

    public function getJournals(){
        return Journal::all();
    }

    public function createJournal($data){
        return Journal::create($data);
    }

    public function updateJournal($data, $id){
        return Journal::where('id', $id)->update($data);
    }

    public function deleteJournal($id){
        return Journal::where('id', $id)->delete();
    }
}
