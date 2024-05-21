<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
			"username" => "sometimes|string|unique:users,username",
			"email" => "sometimes|email|unique:users,email",
            "password" => ["sometimes", "required_with:old_password", "confirmed" ,Password::defaults()],
            "old_password" => ["sometimes", "required_with:password", "same:password"],
        ];
    }

    public function attributes(): array
    {
        return [
            "username" => __("validation.attributes.username"),
            "email" => __("validation.attributes.email"),
            "password" => __("validation.attributes.password"),
        ];
    }
}
