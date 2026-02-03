<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            // email must already exist in users table
            // 'email' => 'required|email|exists:users,email',
            // 'email' => 'required|email|unique:users,email',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($this->id),
            ],
            'password' => 'required|min:4',
            // confirm password
            'confirm-password' => 'required|same:password',
        ];
    }

    // public function messages(): array
    // {
    //     return [
    //         'email.exists' => 'This email does not exist in our records.',
    //         'password_confirmation.same' => 'Password confirmation does not match.',
    //     ];
    // }
}
