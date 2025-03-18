<?php

namespace App\Http\Requests\Admin\HomeDesaBlog;

use Illuminate\Foundation\Http\FormRequest;

class HomeDesaBlogUpdateRequest extends FormRequest
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
            'image' => 'required|string',
            'title' => 'required|string|max:255',
            'sub_title' => 'nullable|max:255|string',
            'content' => 'required|string',
            'is_active' => 'nullable|string',
            'is_general_blog' => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'image' => 'gambar menu',
            'title' => 'judul',
            'sub_title' => 'sub judul',
            'content' => 'konten',
            'is_active' => 'Status Publish',
            'is_general_blog' => 'Status Wilayah',
        ];
    }
}
