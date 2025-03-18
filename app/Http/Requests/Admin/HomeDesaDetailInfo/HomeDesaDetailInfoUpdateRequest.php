<?php

namespace App\Http\Requests\Admin\HomeDesaDetailInfo;

use Illuminate\Foundation\Http\FormRequest;

class HomeDesaDetailInfoUpdateRequest extends FormRequest
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
            'mitigation' => 'required|string',
        ];
    }

    public function attributes(): array
    {
        return [
            'mitigation' => 'Informasi Mitigasi Bencana',
        ];
    }
}
