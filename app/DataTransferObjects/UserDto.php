<?php

namespace App\DataTransferObjects;

use App\Enums\UserRole;
use Carbon\Carbon;
use Illuminate\Support\Facades\Date;

class UserDto
{
    public function __construct(
        public readonly string   $id,
        public readonly string   $username,
        public readonly string   $email,
        public readonly string   $password,
        public readonly UserRole $role,
        public readonly Carbon   $created_at,
        public readonly Carbon   $updated_at,
    )
    {
    }
}
