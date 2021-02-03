<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Romaji implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $value = preg_replace('/\s+/', '', $value);
        $value = preg_replace('/　+/', '', $value);
        if (mb_ereg('^[a-zA-Z]+$', $value)) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '氏名（ローマ字）は半角英字を入力してください。';
    }
}
