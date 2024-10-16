<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Ensure this returns true so the request is authorized
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'max:255',
                'exists:users,email', // Checks if the email exists in the users table
            ],
            'password' => [
                'required',
                'string',
                'min:6', // Password must be at least 6 characters
            ],
        ];
    }
}
