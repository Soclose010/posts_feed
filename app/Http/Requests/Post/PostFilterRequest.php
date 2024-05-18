<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class PostFilterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "title" => "nullable|string",
            "username" => "nullable|string",
            "date" => "nullable|date",
        ];
    }

    public function attributes(): array
    {
        return [
            "title" => __("title"),
            "username" => __("username"),
            "date" => __("date"),
        ];
    }
}
