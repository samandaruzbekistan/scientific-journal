<?php

namespace App\Repositories;

use App\Models\ArticleType;

class ArticleTypeRepository
{
    public function getArticleType($id){
        return ArticleType::where('id', $id)->first();
    }

    public function getArticleTypes(){
        return ArticleType::get();
    }

    public function createArticleType($data){
        return ArticleType::create($data);
    }

    public function updateArticleType($id, $data){
        return ArticleType::where('id', $id)->update($data);
    }

    public function deleteArticleType($id){
        return ArticleType::where('id', $id)->delete();
    }
}
