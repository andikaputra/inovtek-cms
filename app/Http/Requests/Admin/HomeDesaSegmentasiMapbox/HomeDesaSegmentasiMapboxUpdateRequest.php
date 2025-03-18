<?php

namespace App\Http\Requests\Admin\HomeDesaSegmentasiMapbox;

use App\Constants\AppConst;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class HomeDesaSegmentasiMapboxUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'lat_long' => [
                'required',
                'string',
                'max:255',
                'regex:/^-?([1-8]?[0-9](\.\d{1,15})?|90(\.0{1,15})?),\s*-?(1[0-7][0-9](\.\d{1,15})?|[1-9]?[0-9](\.\d{1,15})?)$/',
            ],
            'map_url' => 'nullable|url:https,http',
            'vr_url' => 'nullable|url:https,http',
            'vr_youtube_url' => 'nullable|url:https,http',
            'type' => ['required', Rule::in(AppConst::POINT_TYPE_ARR)],
            'is_active' => 'nullable',
            'is_drone' => 'nullable',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Titik',
            'lat_long' => 'Latitude/Longitude',
            'map_url' => 'Map Url',
            'vr_url' => '360 VR Tour Url',
            'vr_youtube_url' => '360 VR Tour Youtube Url',
            'is_active' => 'Status',
            'type' => 'Jenis Titik',
            'is_drone' => 'Status',
        ];
    }

    public function messages(): array
    {
        return [
            'lat_long.regex' => 'Format :attribute tidak valid. Mohon masukkan dalam format "latitude, longitude", contoh:, "-8.409518, 115.188919".',
        ];
    }
}
