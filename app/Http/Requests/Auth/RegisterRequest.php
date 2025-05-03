<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'full_name' => 'string|required',
            'user_name' => 'string|required',
            'email' => 'email:rfc,filter|required|string|unique:users',
            'password' => 'string|required|confirmed|min:6',
            'password_confirmation' => 'string|required|min:6',
        ];
    }
}
