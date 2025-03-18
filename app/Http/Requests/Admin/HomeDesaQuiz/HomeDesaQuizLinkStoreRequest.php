<?php

namespace App\Http\Requests\Admin\HomeDesaQuiz;

use Illuminate\Foundation\Http\FormRequest;

class HomeDesaQuizLinkStoreRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'quiz_link' => 'required|url:http,https',
            'is_active' => 'nullable|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nama Kuis',
            'quiz_link' => 'Link Kuis',
            'is_active' => 'Status',
        ];
    }
}
