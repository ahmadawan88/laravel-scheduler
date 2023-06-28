<?php
namespace App\Http\Services;

use App\Http\Services\ServiceHelpers\SlotHelper;
use App\Models\Booking;
use App\Models\Service;
use Carbon\Carbon;

class BookingService
{

    public function createBooking(Service $service, Carbon $date, Carbon $startTime, Carbon $endTime, $users) {
        $slot = $this->searchAndGetSlot($service, $date, $startTime, $endTime, count($users));
        if($slot) {
             foreach($users as $user) {
                $user = findOrCreate($user);
                $bookingData[] = [
                    'service_id' => $service->id,
                    'user_id' => $user->id,
                    'date' => $date,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                ];
            }
            return Booking::insert($bookingData);
        } else {
            return false;
        }
    }

    private function searchAndGetSlot(Service $service, Carbon $date, Carbon $slotStartTime, Carbon $slotEndTime, $requiredBookings = 1) {
        $allSlotsOfDate = SlotHelper::getSlotsByDate($service, $date);
        if(!$allSlotsOfDate) {
            return false;
        }
        // dd($allSlotsOfDate, $requiredBookings);
        $checkRequiredSlot = SlotHelper::searchSlot($allSlotsOfDate, $date, $slotStartTime, $slotEndTime, $requiredBookings);
        if(!$checkRequiredSlot) {
            return false;
        }
        return $checkRequiredSlot;
    }
}