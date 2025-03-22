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
        'uz_file',
        'ru_file',
        'en_file',
        'books',
        'article_type_id',
        'user_id',
        'journal_id',
        'status',
    ];

    public function authors(){
        return $this->hasMany(Author::class, 'article_id', 'id');
    }

    public function articleType(){
        return $this->belongsTo(ArticleType::class, 'article_type_id', 'id');
    }
}
