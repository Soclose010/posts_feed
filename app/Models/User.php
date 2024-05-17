<?php

namespace App\Models;

use App\Enums\UserRole;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, HasUuids, SoftDeletes, CascadeSoftDeletes;
    protected $cascadeDeletes = ['posts'];
    protected $dates = ['deleted_at'];
    protected $fillable = [
        "username",
        "email",
        "password",
        "role"
    ];
    protected $hidden = [
        'password',
    ];

    protected $casts = [
        "role" => UserRole::class,
        'password' => 'hashed',
    ];

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function posts()
    {
        return $this->hasMany(Post::class, "user_id","id");
    }

    public function isAdmin(): bool
    {
        return $this->role == UserRole::Admin;
    }
}
