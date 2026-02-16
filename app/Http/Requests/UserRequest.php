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
        // email must already exist in users table
        // 'email' => 'required|email|exists:users,email',
        // 'email' => 'required|email|unique:users,email',
        $rules = [
            'roles'=>'required|array',
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email'),
                // ->ignore($this->user->id),
            ],
        ];
        // Conditionally apply validation rules for password based on whether it's an update or create
        if (request()->isMethod('post')) { // If it's a "create" request (POST)
            $rules['password'] = 'required|min:4'; // Password required for creation
            $rules['confirm-password'] = 'required|same:password'; // Confirm password required for creation
        }
        if(request()->isMethod('put') || request()->isMethod('patch')){
            $rules['password'] = 'nullable|min:4'; // Password is optional for update
            $rules['confirm-password'] = 'nullable|same:password'; // Confirm password is optional for update
        }
        return $rules;
    }

    // public function messages(): array
    // {
    //     return [
    //         'email.exists' => 'This email does not exist in our records.',
    //         'password_confirmation.same' => 'Password confirmation does not match.',
    //     ];
    // }
}
