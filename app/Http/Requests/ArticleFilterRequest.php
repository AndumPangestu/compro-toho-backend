<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleFilterRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'type' => 'nullable|in:news,kindness_story,release,infographics',
            'category_id' => 'nullable|exists:article_categories,id',
            'put_on_highlight' => 'nullable|boolean',
            'limit' => 'nullable|integer|min:1',
            'paginate' => 'nullable|boolean',
            'per_page' => 'nullable|integer|min:1',
            'page' => 'nullable|integer|min:1',
            'search' => 'nullable|string|min:1',

        ];
    }
}
