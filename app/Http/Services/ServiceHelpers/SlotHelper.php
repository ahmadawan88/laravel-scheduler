<?php
namespace App\Http\Services\ServiceHelpers;

use App\Models\Service;
use Carbon\Carbon;

class SlotHelper {

    public static function isSlotAvailable(Service $service, Carbon $date, Carbon $startTime, Carbon $endTime, $requiredBookings = false) {
        $openingHours = $service->getOpeningHours($date);
        if (!$service->isWithinOpeningHours($startTime, $endTime, $openingHours)) {
            return ['status' => false, 'case' => 'notInOpeningHours', 'data' => null];
        }
        $holiday = $service->isPublicHoliday($startTime, $endTime);
        if($holiday) {
            return ['status' => false, 'case' => 'isPublicHoliday', 'data' => $holiday];
        }
        $break = $service->isWithinBreak($startTime, $endTime);
        if($break) {
            return ['status' => false, 'case' => 'isWithinBreak', 'data' => $break];
        }
        $existingBookingsCount = $service->getExistingBookingsCount($date, $startTime, $endTime);
        $hasCapacity = self::hasCapacity($service->capacity, $existingBookingsCount, $requiredBookings);
        if($hasCapacity) {
            return ['status' => true, 'case' => 'slotAvailable', 'data' => $service->capacity - $existingBookingsCount];    
        } 
        return ['status' => false, 'case' => 'insufficientCapacity', 'data' => $service->capacity - $existingBookingsCount];
    }

    public static function hasCapacity($total, $existing, $required) {
        $availableCapicity = $total - $existing;
        if($required) {
            $availableCapicity -= $required;
        }
        return $availableCapicity > 0;     
    }

    public static function searchSlot($slots, $date, $startTime, $endTime, $requiredBookings = 0) {
        return collect($slots)
                    ->where('date', $date->format(config('settings.dateFormat')))
                    ->where('startTime', $startTime->format(config('settings.timeFormat')))
                    ->where('endTime', $endTime->format(config('settings.timeFormat')))
                    ->where('remainingCapacity', '>=', $requiredBookings)
                    ->first();
    }

    public static function getSlotsByDate($service, $date, $includeUnavailable = false) {
        $openingHours = $service->getOpeningHours($date);
        if (!$openingHours) {
            return false;
        }
        $slotDuration = $service->slot_duration + $service->clean_time;
        $time = $date->copy()->setTimeFrom(Carbon::parse($openingHours['start_time']));
        $endTime = $date->copy()->setTimeFrom(Carbon::parse($openingHours['end_time']));
        $slots = [];
        $i = 1;
        while ($time < $endTime) {
            $result = self::checkSlotAvailability($service, $date, $time, $slotDuration);
            switch ($result['case']) {
                case 'slotAvailable':
                    $capacity = $result['data'];
                    $slots[] = self::createSlotData($time, $slotDuration, $date, $capacity);
                    $time = self::getNextTime($time, $slotDuration);
                    break;
            
                case 'isWithinBreak':
                    $time = self::getTimeAfterBreak($time, $result['data']->end_time);
                    break;
            
                case 'isPublicHoliday':
                    $time = self::getTimeAfterHoliday($time, $result['data']->end_date);
                    break;
                case 'notInOpeningHours': // can handle additional cases, not needed at the moment     
                case 'insufficientCapacity':
                case 'isWithinExistingBooking':
            
                default:
                    $time = self::getNextTime($time, $slotDuration);
                    break;
            }
            $i++;      
        }
        return $slots;
    }
    public static function checkSlotAvailability(Service $service, Carbon $date, Carbon $time, $slotDuration) {
        $checkEndTime = $time->copy()->addMinutes($slotDuration);
       return SlotHelper::isSlotAvailable($service, $date, $time, $checkEndTime);
    }

    public static function createSlotData(Carbon $time, $slotDuration, Carbon $date, $capacity) {
        return [
            'startTime' => $time->format(config('settings.timeFormat')),
            'endTime' => $time->copy()->addMinutes($slotDuration)->format(config('settings.timeFormat')),
            'date' => $date->format(config('settings.dateFormat')),
            'day' => $date->englishDayOfWeek,
            'remainingCapacity' => $capacity,
        ];
    }

    public static function getNextTime(Carbon $time, $slotDuration) {
        return $time->addMinutes($slotDuration);
    }

    public static function getTimeAfterBreak(Carbon $time, $endTime) {
        $newTime = Carbon::parse($endTime);
        $newTime->setDate($time->year, $time->month, $time->day);
        return $newTime;
    }

    public static function getTimeAfterHoliday(Carbon $time, $endTime) {
        return $newTime = Carbon::parse($endTime);
        $newTime->setDate($time->year, $time->month, $time->day);
        return $newTime;
    }

}