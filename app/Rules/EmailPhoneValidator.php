<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class EmailPhoneValidator implements InvokableRule
{

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        // Check the passed value is Email or Not
        if (strpos($value, '@') !== false && (!filter_var($value, FILTER_VALIDATE_EMAIL))) {
            $fail('The Email is not Valid!');
        }
        else
        {
            if(!$this->validate_mobile($value)) {
                $fail('The Phone Number is not Valid!');
            }
        }
    }

    private function validate_mobile($mobile)
    {
        return preg_match('/^[0-9]{10}+$/', $mobile);
    }
}
