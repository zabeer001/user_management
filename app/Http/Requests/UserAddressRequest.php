<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;



class UserAddressRequest extends FormRequest
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
            'user_id' => 'required|exists:users,id', // Ensure user_id exists in users table
            'address_line' => 'required|string|max:255', // Validate address line
            'city' => 'required|string|max:100', // Validate city name
            'state' => 'required|string|max:100', // Validate state name
            'postal_code' => 'required|string|max:20', // Validate postal/ZIP code
            'country' => 'required|string|max:100', // Validate country name
        ];
    }
}
