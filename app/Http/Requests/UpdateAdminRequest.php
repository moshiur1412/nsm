<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            //'email' => 'required|string|email|max:255|unique:admins,email,'.$this->admins.',id',
            //'email' => 'required|string|email|max:50|unique:admins,email,'.$id,
        ];
    }

    public function withValidator($validator)
    {
        \Log::error('Error: UpdateAdminRequest validation failed!');
    }
}
