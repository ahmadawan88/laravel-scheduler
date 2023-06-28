<?php

namespace App\Http\Requests;

use App\Rules\ValidTimeFormatRule;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class createBookingRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    
    public function rules()
    {
        return [
            'service_id' => 'required|exists:services,id',
            'date' => [
                'required',
                'date_format:'.config('settings.dateFormat'),
                'after_or_equal:' . now()->format(config('settings.dateFormat')),
            ],
            'service_id' => 'required|exists:services,id',
            'start_time' => [
                'required',
                'regex:/^((1[0-9])|(2[0-3])|([01]?[0-9])):[0-5][0-9](\s?[AP]M)?$/i',
                // 'after_or_equal:'. Carbon::parse($this->date)->setTimeFrom(now())->format(config("settings.timeFormat"))// . now()->format(config('settings.timeFormat'))
                // Rule::passes(function ($attribute, $value) {
                //     $startTime = Carbon::parse($this->date)->setTimeFrom(now());
                //     dd($startTime);
                // }),
            ],
            'end_time' => [
                'required',
                'regex:/^((1[0-9])|(2[0-3])|([01]?[0-9])):[0-5][0-9](\s?[AP]M)?$/i',
                // 'after:start_time'
            ],
            'customers' => 'required|array|min:1',
            'customers.*.email' => 'required|email',
            'customers.*.first_name' => 'required',
            'customers.*.last_name' => 'required',
        ];
    }

    public function messages() {
        return [
            'required' => 'The :attribute field is required.',
        ];
    }

    // protected function prepareForValidation() {
    //     $this->merge([
    //         'start_time' => Carbon::createFromFormat('H:i', $this->start_time),
    //         'end_time' => Carbon::createFromFormat('H:i', $this->end_time),
    //     ]);
    // }
}
