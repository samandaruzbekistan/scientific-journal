<?php

namespace App\Repositories;

use App\Models\Article;

class ArticleRepository
{
    public function getArticle($id){
        return Article::where('id', $id)->first();
    }

    public function getArticlesByUserId($user_id){
        return Article::where('user_id', $user_id)->get();
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

    public function getArticlesByStatus($status){
        return Article::where('status', $status)->get();
    }

    public function getArticlesByStatusAndType($status, $type){
        return Article::where('status', $status)->where('article_type_id', $type)->get();
    }
}
