<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Journal extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_uz',
        'name_ru',
        'name_en',
        'issn',
        'cover_image_uz',
        'cover_image_ru',
        'cover_image_en',
        'number',
        'year',
    ];
}
