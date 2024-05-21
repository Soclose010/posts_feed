<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class PostCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            "title" => "required|string",
            "body" => "required|string"
        ];
    }

    public function attributes(): array
    {
        return [
            "title" => __("validation.attributes.title"),
            "body" => __("validation.attributes.body"),
        ];
    }
}
