<?php

namespace App\Http\Requests\Admin\User;

use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
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
            'email' => 'required|unique:users,email|email',
            'name' => 'required|string|max:266',
            'password' => 'required|confirmed|min:8',
            'role_access' => 'required|string|in:super_admin,admin',
            'is_active' => 'nullable',
        ];
    }
}
