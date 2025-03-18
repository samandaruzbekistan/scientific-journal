<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EditorialsTeam extends Model
{
    use HasFactory;

    protected $fillable = ['article_type_id', 'name', 'json_data'];
}
