<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class SendOtpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return false;
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
          'phone' => [
                'required',
                'regex:/^[6-9]\d{9}$/'
             ]
        ];
    }

// messages for error
    public function messages(): array
    {
        return [
            'phone.required' => 'Phone number is required',
            'phone.regex' => 'Invalid phone number'
        ];
    }
}
