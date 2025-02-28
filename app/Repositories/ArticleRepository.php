<?php

namespace App\Repositories;

use App\Models\Article;

class ArticleRepository
{
    public function getArticle($id){
        return Article::where('id', $id)->first();
    }

    public function getArticles(){
        return Article::latest()->get();
    }

    public function createArticle($data){
        return Article::create($data);
    }

    public function updateArticle($data, $id){
        return Article::where('id', $id)->update($data);
    }

    public function deleteArticle($id){
        return Article::where('id', $id)->delete();
    }
}
