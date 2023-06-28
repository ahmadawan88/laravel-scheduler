<?php
namespace App\Http\Services;

use App\Http\Services\ServiceHelpers\SlotHelper;
use App\Models\Service;
use Carbon\Carbon;

class SlotService
{
    public function getSlots(Service $service, Carbon $fromDate, Carbon $endDate) {
        $slots = [];
        $currentDate = $fromDate->copy();
        while($currentDate->lte($endDate)) {
            $slotsOfDay = SlotHelper::getSlotsByDate($service, $currentDate);
            if($slotsOfDay) {
                $slots[] = [
                    "date"  => $currentDate->format(config('settings.dateFormat')),
                    "available_slots" => $slotsOfDay
                ];
            } else {
                $slots[] = [
                    "date"  => $currentDate->format(config('settings.dateFormat')),
                    "available_slots" => []
                ];
            }
            $currentDate->addDay();  
        }
        return $slots;
    }
}