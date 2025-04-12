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

    public function increment_article_count($id){
        $journal = Journal::where('id', $id)->first();
        $article_count = $journal->article_count;
        $article_count++;
        return Journal::where('id', $id)->update(['article_count' => $article_count]);
    }

    public function change_status_to_active($id){
        return Journal::where('id', $id)->update(['status' => 'active']);
    }

    public function change_status_to_completed_all(){
        return Journal::where('status', 'active')->update(['status' => 'completed']);
    }
}
