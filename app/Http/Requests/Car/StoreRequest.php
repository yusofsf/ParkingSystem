<?php

namespace App\Http\Requests\Car;

use App\Models\Car;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Car::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'model' => 'string|required',
            'color' => 'string|required',
            'license_plate_number' => 'string|required|unique:cars',
            'date_of_manufacture' => 'integer|required'
        ];
    }
}
