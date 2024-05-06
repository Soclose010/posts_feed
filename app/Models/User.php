<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, HasUuids;


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        "role" => UserRole::class,
        'password' => 'hashed',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class, "user_id","id");
    }
}
