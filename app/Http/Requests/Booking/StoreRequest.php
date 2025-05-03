<?php

namespace App\Http\Requests\Booking;

use App\Models\Booking;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('book', Booking::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'begin' => 'required|date',
            'end' => 'required|date',
            'car_id' => 'integer|exists:cars,id',
            'slot_id' => 'integer|exists:parking_slots,id',
        ];
    }
}
