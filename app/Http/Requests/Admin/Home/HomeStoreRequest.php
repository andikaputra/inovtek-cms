<?php

namespace App\Http\Requests\Admin\Home;

use Illuminate\Foundation\Http\FormRequest;

class HomeStoreRequest extends FormRequest
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
            'province' => 'required|string|max:255',
            'regency' => 'required|string|max:255',
            'product' => 'required|array|min:1',
            'product.*' => 'required|exists:existing_apps,id',
            'is_active' => 'nullable',
            'image' => 'required|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'province' => 'Provinsi',
            'regency' => 'Kabupaten/Wilayah',
            'product' => 'Produk Tersedia',
            'is_active' => 'Status',
            'image' => 'Wallpaper',
        ];
    }
}
