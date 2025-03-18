<?php

namespace App\Http\Requests\Admin\SeoManagement;

use App\Constants\SeoConst;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SeoManagementStoreUpdateRequest extends FormRequest
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
            'meta_title' => 'required|string|max:255',
            'meta_robot' => 'required|string|max:255',
            'meta_author' => 'required|string|max:255',
            'meta_keyword' => 'required|string|max:500',
            'meta_language' => 'required|string|max:255',
            'meta_description' => 'required|string|max:1500',
            'meta_og_title' => 'nullable|string|max:255',
            'meta_og_url' => 'nullable|url:http,https',
            'meta_og_type' => ['nullable', Rule::in(SeoConst::SEO_TYPE_ARR)],
            'meta_og_description' => 'nullable|string|max:1500',
        ];
    }
}
