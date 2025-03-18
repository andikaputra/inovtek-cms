<?php

namespace App\Http\Requests\API\HomeDesaQuiz;

use App\Helpers\Json;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class HomeDesaQuizRegistrationRequest extends FormRequest
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
            'email' => 'required|email|max:255',
            'phone_no' => 'required|string|min:9|max:16',
            'sex_type' => 'required|string|in:L,P',
            'age' => 'required|string|in:6-12,13-17,18-22,23-30,31-40,41-50,50 ke atas',
            'village_id' => 'required|exists:region_details,id',
            'work' => 'required|string|max:1500',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'Nama',
            'email' => 'Email',
            'phone_no' => 'No. Telp',
            'sex_type' => 'Jenis Kelamin',
            'age' => 'Umur',
            'village_id' => 'Desa/Kelurahan',
            'work' => 'Pekerjaan',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(Json::error(error: $validator->errors()->toArray(), httpCode: Response::HTTP_BAD_REQUEST));
    }
}
