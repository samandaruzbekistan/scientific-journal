<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'status',
        'user_id',
        'journal_id',
        'article_id',
        'article_type_id',
        'price',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function journal(){
        return $this->belongsTo(Journal::class, 'journal_id', 'id');
    }

    public function article(){
        return $this->belongsTo(Article::class, 'article_id', 'id');
    }

    public function articleType(){
        return $this->belongsTo(ArticleType::class, 'article_type_id', 'id');
    }

}
