<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        "title",
        "body",
        "user_id"
    ];

    public static function getTableName()
    {
        return with(new static)->getTable();
    }

    public function scopeUsername(Builder $query): Builder
    {
        $table = User::getTableName();
        return $query->
        addSelect("$table.username")->
        join("users", "users.id", "=", "posts.user_id");
    }

    public function user()
    {
        return $this->belongsTo(User::class, "user_id", "id");
    }
}
