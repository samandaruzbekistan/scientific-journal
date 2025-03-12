<?php

namespace App\Repositories;

use App\Models\Journal;

class JournalRepository
{
    public function getJournal($id){
        return Journal::where('id', $id)->first();
    }

    public function getJournals(){
        return Journal::latest()->get();
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

    public function getActiveJournal(){
        return Journal::where('status', 'active')->first();
    }
    public function change_status_to_completed($id){
        return Journal::where('id', $id)->update(['status' => 'completed']);
    }
}
