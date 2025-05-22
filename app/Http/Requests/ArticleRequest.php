<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return optional($this->user())->role === 'admin' || optional($this->user())->role === 'superadmin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255|unique:articles,title' . ($this->route('article') ? ',' . $this->route('article')->id : ''),
            'content' => 'nullable|string',
            'type' => 'required|in:news,kindness_story,release,infographics',
            'description' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:article_categories,id',
            'donation_id' => 'nullable|exists:donations,id',
            'put_on_highlight' => 'boolean',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'tags' => 'nullable|string',
        ];
    }


    public function messages()
    {
        return [
            'title.required' => 'The title field is required.',
            'type.required' => 'The type field is required.',
            'image.image' => 'Image must be an image',
            'image.mimes' => 'Image must be a file of type: jpeg, png, jpg, gif',
            'image.max' => 'Image must not exceed 4MB',
        ];
    }
}
