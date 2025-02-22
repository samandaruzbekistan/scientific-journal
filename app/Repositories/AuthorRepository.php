<?php

namespace App\Repositories;

use App\Models\Author;

class AuthorRepository
{
    public function getAuthor($id){
        return Author::where('id', $id)->first();
    }

    public function getAuthors(){
        return Author::latest()->get();
    }

    public function createAuthor($data){
        return Author::create($data);
    }

    public function updateAuthor($data, $id){
        return Author::where('id', $id)->update($data);
    }

    public function deleteAuthor($id){
        return Author::where('id', $id)->delete();
    }
}
