<?php

namespace App\Http\Controllers;

use App\Http\Requests\createBookingRequest;
use App\Http\Services\BookingService;
use App\Models\Service;
use Carbon\Carbon;

class BookingsController extends Controller
{
    public function create(createBookingRequest $request, BookingService $bookingService) {
        $service = Service::find($request->input("service_id"));
        $date = Carbon::parse($request->input('date'))->startOfDay();
        $startTime  = $date->copy()->setTimeFrom($request->start_time);
        $endTime = $date->copy()->setTimeFrom($request->end_time);

        if($startTime->isPast() || $endTime->isPast() || $endTime->lte($startTime)) {
            return sendError('Invalid time given');
        }

        if($endTime->diffInMinutes($startTime) != ($service->slot_duration + $service->clean_time)) {
            return sendError('Invalid slot, duration must be atleast '. ($service->slot_duration + $service->clean_time). ' minutes');
        }

        $booking = $bookingService->createBooking($service, $date, $startTime, $endTime, $request->customers);
        if($booking) {
            return sendSuccess($booking, 'Bookings created successfully');
        } else {
            return sendError('Invalid slot');
        }
    }
}
