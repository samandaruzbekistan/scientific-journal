<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'last_name',
        'orcid',
        'roles', // Add this line
        'email',
        'academic_degree',
        'institution',
        'country',
    ];

    public function article(){
        return $this->belongsTo(Article::class);
    }
}
