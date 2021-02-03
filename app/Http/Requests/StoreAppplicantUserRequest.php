<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Kana;
use App\Rules\SpaceIn;
use App\Rules\NoSymbol;

class StoreAppplicantUserRequest extends FormRequest
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
            'name' => ['required','string',new NoSymbol($this->request->all()),new SpaceIn($this->request->all())],
			'name_kana' => ['required','string',new Kana($this->request->all()),new NoSymbol($this->request->all()),new SpaceIn($this->request->all())],
			'belong_type_name' => 'required|string|min:2|max:255',
			'belongs' => 'required|string|min:2|max:255',
			'major' => 'required|string|min:2|max:255',
            'email' => 'required|string|email|max:255|unique:applicants,email',
            'password' => 'required',

        ];
    }

    public function withValidator($validator)
    {
        \Log::error('Error: StoreAppplicantUserRequest validation failed!');
    }
}
