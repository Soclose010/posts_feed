<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use App\Enums\Action as ActionEnum;
use Illuminate\Database\Eloquent\SoftDeletes;

class Action extends Model
{
    use HasUuids, SoftDeletes;

    protected $fillable = [
        "actor",
        "owner",
        "old",
        "new",
        "action"
    ];

    protected $casts = [
        "action" => ActionEnum::class
    ];
}
