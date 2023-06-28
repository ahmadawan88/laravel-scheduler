<?php

namespace Database\Seeders;

use App\Http\Services\ServiceHelpers\SlotHelper;
use App\Models\Booking;
use App\Models\BusinessAdministrator;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        $serviceSeedData = config('seedSettings.serviceSeedData');
        $bookingsData = [];
        foreach($serviceSeedData as $service) {
            $service = Service::where('name' , $service['service']['name'])->first();
            $currentDate = Carbon::now()->startOfDay();
            $endDate = $currentDate->copy()->addDays($service->booking_time_limit);
            $user = findOrCreate(config('seedSettings.mockUser'));
            while($currentDate->lte($endDate)) {
                $slotsOfDay = SlotHelper::getSlotsByDate($service, $currentDate);
                if($slotsOfDay) {
                    foreach ($slotsOfDay as $slotData) {
                        while($slotData['remainingCapacity'] > 0 ) {
                            $bookingsData[] = [
                                'start_time' => Carbon::parse($slotData['startTime']),
                                'end_time' => Carbon::parse($slotData['endTime']),
                                'date' => $currentDate->copy()->startOfDay(),
                                'service_id' => $service->id,
                                'user_id' => $user->id,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ];
                            $slotData['remainingCapacity'] --;
                        }
                    }
                }
                $currentDate->addDay();  
            }
        }
        Booking::insert($bookingsData);
    }
}
