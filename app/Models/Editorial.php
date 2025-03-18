<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Editorial extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles;

    protected $fillable = ['first_name', 'last_name', 'email', 'phone', 'password', 'status', 'article_type_id', 'role'];

    protected $guard_name = 'editorial';
}
