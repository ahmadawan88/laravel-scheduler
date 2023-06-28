<?php

namespace App\Rules;

use Carbon\Carbon;
use Illuminate\Contracts\Validation\Rule;

class ValidTimeFormatRule implements Rule
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
        try {
            return preg_match('/^((1[0-9])|(2[0-3])|([01]?[0-9])):[0-5][0-9](\s?[AP]M)?$/i', $value);
            $time = Carbon::createFromFormat('H:i:s', $value);
            dd($time, 'in rule');
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The :attribute field does not have a valid time format.';
    }
}
