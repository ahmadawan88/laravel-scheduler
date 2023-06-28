<?php

namespace App\Http\Requests;

class getSlotsRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {   
        return [
            'service_id' => 'required|exists:services,id',
            'from' => [
                // 'required',
                'date_format:' . config('settings.dateFormat'),
                'after_or_equal:' . now()->format(config('settings.dateFormat')),
            ],
            'to' => [
                // 'required',
                'date_format:' . config('settings.dateFormat'),
                'after_or_equal:from',
            ],
        ];
    }
    
    public function messages() {
        return [
        ];
    }
}
