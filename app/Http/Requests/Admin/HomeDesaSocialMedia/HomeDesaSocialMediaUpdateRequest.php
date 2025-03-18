<?php

namespace App\Http\Requests\Admin\HomeDesaSocialMedia;

use Illuminate\Foundation\Http\FormRequest;

class HomeDesaSocialMediaUpdateRequest extends FormRequest
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
            'url' => 'required|url:http,https',
            'display' => 'required|string|max:255',
            'is_active' => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'image' => 'Icon',
            'url' => 'Url',
            'display' => 'Display',
            'is_active' => 'Status',
        ];
    }
}
