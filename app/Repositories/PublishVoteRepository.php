<?php

namespace App\Repositories;

use App\Models\PublishVote;

class PublishVoteRepository
{
    public function getPublishVote($id){
        return PublishVote::where('id', $id)->first();
    }

    public function getVoteByArticleAndEditorial($article_id, $editorial_id){
        return PublishVote::where('article_id', $article_id)->where('editorial_id', $editorial_id)->first();
    }

    public function getPublishVotes(){
        return PublishVote::latest()->get();
    }

    public function createPublishVote($data){
        return PublishVote::create($data);
    }

    public function getByEditorialId($editorial_id){
        return PublishVote::where('editorial_id', $editorial_id)->get();
    }

    public function updatePublishVote($data, $id){
        return PublishVote::where('id', $id)->update($data);
    }

    public function deletePublishVote($id){
        return PublishVote::where('id', $id)->delete();
    }

    public function getPublishVotesByArticleId($article_id){
        return PublishVote::where('article_id', $article_id)->get();
    }

    public function getPublishVotesByEditorialId($editorial_id){
        return PublishVote::where('editorial_id', $editorial_id)->get();
    }
}
