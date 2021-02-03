<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;
use App\Rules\Kana;
use App\Rules\Romaji;
use App\Rules\SpaceIn;
use App\Rules\NoSymbol;
use DateTime;

class UpdateAwardApplicationRequest extends FormRequest
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
        $request = $this->request->all();
        return [
            'name' => ['required','string',new NoSymbol($this->request->all()),new SpaceIn($this->request->all())],
            'name_kana' => ['required','string',new NoSymbol($this->request->all()),new SpaceIn($this->request->all()),new Kana($this->request->all())],
            'name_alphabet' => ['required','string',new Romaji($this->request->all()),new NoSymbol($this->request->all()),new SpaceIn($this->request->all())],
            'birthday' => ['required','date',function ($attribute, $value, $fail) use (&$request) {
              if(strptime(array_get($request,'birthday'), '%Y-%m-%d') && array_get($request,'subsidy_granted_year') !=null){
                $year = date("Y");
                $subsidy_granted_year = (int)date("Y",strtotime(array_get($request,'subsidy_granted_year')));
                $birthday = new DateTime(array_get($request,'birthday'));
                // \Log::info(array_get($request,'birthday'));
                if ($year - $subsidy_granted_year == 3) {
                  $diff = $birthday->diff(new DateTime("{$year}-10-31"));
                  if ($diff->y > 42 ) {
                    $fail('入力された生年月日は応募規定を満たしておりません');
                  }
                }
                if ($year - $subsidy_granted_year == 4) {
                  $diff = $birthday->diff(new DateTime("{$year}-10-31"));
                  if ($diff->y > 43 ) {
                    $fail('入力された生年月日は応募規定を満たしておりません');
                  }
                }
              }
            }],
            'belongs'=>'required',
            'belong_type_name'=>'required',
            'major'=>'required',
            'occupation'=>'required',
            'occupation_other'=>'required_if:occupation,"その他"',
            'belongs'=>'required',
            'major'=>'required',
            'zip_code'=>'required|string',
            'address1'=>'required|string',
            'theme' => 'required|string|max:40',
            'subsidy_granted_year' => 'required',
            'attachment'=>'mimes:pdf|max:5000',
        ];
    }

    public function withValidator($validator)
    {
        \Log::error('Error: UpdateAwardApplicationRequest validation failed!');
    }
}
