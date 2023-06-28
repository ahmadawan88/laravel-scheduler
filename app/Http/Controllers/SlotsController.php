<?php

namespace App\Http\Controllers;

use App\Http\Requests\getSlotsRequest;
use App\Http\Services\SlotService;
use App\Models\Service;
use Carbon\Carbon;

class SlotsController extends Controller
{
    public function index(getSlotsRequest $request, SlotService $slotService) {
        
        $service = Service::find($request->input('service_id'));
        $from = Carbon::parse($request->input('from', now()->format(config("settings.dateFormat"))));
        $to = Carbon::parse($request->input('to', now()->addDays($service->booking_time_limit)->format((config("settings.dateFormat")))));
        
        
        if(!$service->checkBookingEndDate($to)) {
            return sendError(
                'End date must be before or equal to '. now()->addDays($service->booking_time_limit)->format(config('settings.dateFormat'))
            ); 
        }
        return sendSuccess($slotService->getSlots($service, $from, $to));
    }
}
