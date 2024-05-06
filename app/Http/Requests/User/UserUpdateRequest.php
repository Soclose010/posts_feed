<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
			"username" => "string",
			"email" => "email|unique:users,email",
            "old_password" => ["required_with:password", Password::defaults()],
			"password" => ["required_with:old_password", Password::defaults()],
        ];
    }
}
