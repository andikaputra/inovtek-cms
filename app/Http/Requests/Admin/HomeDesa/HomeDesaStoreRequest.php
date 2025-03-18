<?php

namespace App\Http\Requests\Admin\HomeDesa;

use Illuminate\Foundation\Http\FormRequest;

class HomeDesaStoreRequest extends FormRequest
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
            'type' => 'required|in:default,custom',
            'village' => 'required|string|max:255',
            'lat_long' => [
                'required_if:type,default',
                'string',
                'max:255',
                'regex:/^-?([1-8]?[0-9](\.\d{1,15})?|90(\.0{1,15})?),\s*-?(1[0-7][0-9](\.\d{1,15})?|[1-9]?[0-9](\.\d{1,15})?)$/',
            ],
            'map_url' => 'nullable|url:https,http',
            'is_active' => 'nullable',
        ];
    }

    public function attributes(): array
    {
        return [
            'type' => 'Jenis Produk',
            'village' => 'Desa/Kelurahan',
            'lat_long' => 'Latitude/Longitude',
            'map_url' => 'Map Url',
            'is_active' => 'Status',
        ];
    }

    public function messages(): array
    {
        return [
            'lat_long.regex' => 'Format :attribute tidak valid. Mohon masukkan dalam format "latitude, longitude", contoh:, "-8.409518, 115.188919".',
        ];
    }
}
