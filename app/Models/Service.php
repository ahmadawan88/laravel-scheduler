<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;


class Service extends Model
{
    use HasFactory;
    protected $table = 'services';
    protected $fillable = ['name', 'capacity', 'clean_time', 'slot_duration', 'booking_time_limit', 'business_administrator_id'];
    public $timestamps = ['created_at', 'updated_at'];
    
    
    public function days() {
        return $this->hasMany(ServiceWorkingDay::class, 'service_id', 'id');
    }

    public function breaks() {
        return $this->hasMany(ServiceBreak::class, 'service_id', 'id');
    }

    public function holidays() {
        return $this->hasMany(ServiceHoliday::class, 'service_id', 'id');
    }

    public function bookings() {
        return $this->hasMany(Booking::class, 'service_id', 'id');
    }
    
    ////////////////////////////////////////////////////////////////
    public function checkBookingEndDate(Carbon $toDate) {
        if($toDate > now()->addDays($this->booking_time_limit)) {
            return false;
        }
        return true;
    }
    
    public function getOpeningHours(Carbon $date) {
        $openingHours =  $this->days()->where('day', $date->englishDayOfWeek)->first();
        if($openingHours) {
            $start_time  = $date->copy()->setTimeFrom($openingHours->start_time);
            $end_time = $date->copy()->setTimeFrom($openingHours->end_time);
            return ['start_time' => $start_time, 'end_time' => $end_time];
        }
        return null;
    }

    public function isPublicHoliday(Carbon $startTime, Carbon $endTime) {
        $holiday = $this->holidays()->whereDate("date", $startTime)->first();
        if($holiday) {
            $holiday->end_date = $startTime->copy()->startOfDay()->addDay(); 
            if($holiday->start_time == null || $holiday->end_time == null) { //full day Holiday
                return $holiday;
            }
            $holidayStartTime =  $startTime->copy()->setTimeFrom($holiday->start_time);
            $holidayEndTime   =  $endTime->copy()->setTimeFrom($holiday->end_time);
            $holiday->end_date = $holidayEndTime->copy();
            if($startTime->gte($holidayStartTime) && $startTime->lt($holidayEndTime)) {
                return $holiday;
            } else if ($endTime->gte($holidayStartTime) && $endTime->lte($holidayEndTime)) {
                return $holiday;
            }
        } 
        return false;
    }


    function isWithinOpeningHours($startTime, $endTime, $openingHours) {
        if(!$openingHours) {
            return false;
        }
        $startOpeningHour = Carbon::parse($openingHours['start_time']);
        $endOpeningHour = Carbon::parse($openingHours['end_time']);
        return $startTime >= $startOpeningHour && $endTime <= $endOpeningHour;
    }

    public function isWithinBreak(Carbon $time, Carbon $endTime) {
        $breakTimes = $this->breaks;

        foreach ($breakTimes as $breakTime) {
            $startTime = Carbon::parse($breakTime->start_time)
                ->setYear($time->format('Y'))
                ->setMonth($time->format('m'))
                ->setDay($time->format('d'));
            $endTime = Carbon::parse($breakTime->end_time)
                ->setYear($time->format('Y'))
                ->setMonth($time->format('m'))
                ->setDay($time->format('d'));
                
            if (
                ($time->gte($startTime) && $time->lt($endTime))
                 || 
                ($time->lt($startTime) && $time->copy()->addMinutes($this->clean_time + $this->slot_duration)->gt($startTime))
            ) {
                return $breakTime;
            }
        }

        return false;
    }
    // public function isWithinExistingBooking(Carbon $date, Carbon $startTime, Carbon $endTime) {
    //     return $this->bookings()->where('service_id', $this->id)
    //     ->whereDate('date', $date)
    //     ->where(function ($query) use ($startTime, $endTime) {
    //         $query->where(function ($query) use ($startTime, $endTime) {
    //             $query->whereTime('start_time', '<', $endTime)
    //                 ->whereTime('end_time', '>', $startTime);
    //         })->orWhere(function ($query) use ($startTime, $endTime) {
    //             $query->whereTime('start_time', '>=', $startTime)
    //                 ->whereTime('start_time', '<', $endTime);
    //         })->orWhere(function ($query) use ($startTime, $endTime) {
    //             $query->whereTime('end_time', '>', $startTime)
    //                 ->whereTime('end_time', '<=', $endTime);
    //         });
    //     })->first();
    // }
    public function getExistingBookingsCount($date, $startTime, $endTime) {
        return $this->bookings()->whereDate('date', $date)->whereTime('start_time', $startTime)->count();
    }

}
