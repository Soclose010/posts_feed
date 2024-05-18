<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "username" => "required|string|unique:users,username",
			"email" => "required|email|unique:users,email",
			"password" => ["required", "confirmed", Password::defaults()],
        ];
    }

    public function attributes(): array
    {
        return [
            "username" => __("username"),
            "email" => __("email"),
            "password" => __("password"),
        ];
    }
}
