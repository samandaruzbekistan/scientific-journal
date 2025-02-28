<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_uz',
        'title_ru',
        'title_en',
        'keywords_uz',
        'keywords_ru',
        'keywords_en',
        'abstract_uz',
        'abstract_ru',
        'abstract_en',
        'body_uz',
        'body_ru',
        'body_en',
        'file_uz',
        'file_ru',
        'file_en',
        'books'
    ];

    public function authors(){
        return $this->hasMany(Author::class, 'article_id', 'id');
    }
}
