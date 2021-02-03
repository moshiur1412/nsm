<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' =>'required|email',
            'new-password' => 'required|min:6',
            'new-password_confirmation' => 'required|min:6|same:new-password',
        ];


    }
    public function messages()
    {
        return [
            'new-password.required' => 'The password field must be required.',
            'new-password.min' => 'The password must be at least 6 characters.',
            'new-password_confirmation.required' => 'The Password Confirmation field must be required.',
            'new-password_confirmation.min' => 'The Password Confirmation must be at least 6 characters.',
            'new-password_confirmation.same' => 'The Password Confirmation should match the Password.',
        ];
    }
}
