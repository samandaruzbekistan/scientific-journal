<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PublishVote extends Model
{
    use HasFactory;

    protected $fillable = [
        'article_id',
        'editorial_id',
        'comment',
        'status',
        'vote',
    ];
}
