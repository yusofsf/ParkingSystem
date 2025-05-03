<?php

namespace App\Http\Requests\ParkingSlot;

use App\Models\ParkingSlot;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', ParkingSlot::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'string|required',
            'price_per_day' => 'numeric|required',
            'price_per_hour' => 'numeric|required',
            'type' => 'digits:1|required',
            'available' => 'boolean',
        ];
    }
}
