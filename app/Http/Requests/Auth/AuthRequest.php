<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "email" => "required|email",
            "password" => "required|string"
        ];
    }

    public function attributes(): array
    {
        return [
            "email" => __("validation.attributes.email"),
            "password" => __("validation.attributes.password"),
        ];
    }
}
