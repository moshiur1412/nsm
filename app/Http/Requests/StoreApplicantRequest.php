<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\Kana;
use App\Rules\SpaceIn;
use App\Rules\NoSymbol;

class StoreApplicantRequest extends FormRequest
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
            'user-email' =>'required|unique:applicants,email',
            'user-password' => 'required|min:6',
            'user-password_confirmation' => 'required|min:6|same:user-password',
        ];
    }

    public function messages()
    {
        return [
            'user-email.required' => 'メールアドレスは必須です',
            'user-password.required' => 'パスワードは必須です',
            'user-password.min' => '６文字以上のパスワードを指定してください',
            'user-password_confirmation.required' => 'パスワード確認用は必須です',
            'user-password_confirmation.min' => '６文字以上のパスワードを指定してください',
            'user-password_confirmation.same' => 'パスワードとパスワード確認用が一致しません',
        ];
    }
}
