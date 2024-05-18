<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class PostUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "title" => "string",
            "body" => "string"
        ];
    }

    public function attributes(): array
    {
        return [
            "title" => __("title"),
            "body" => __("body"),
        ];
    }
}
