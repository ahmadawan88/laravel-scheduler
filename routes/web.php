<?php

use App\Http\Services\ServiceHelpers\SlotHelper;
use App\Models\Booking;
use App\Models\BusinessAdministrator;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    // $admin = BusinessAdministrator::where('name', config('seedSettings.mockAdminName'))->first();
    // $serviceSeedData = config('seedSettings.serviceSeedData');
    // foreach ($serviceSeedData as $serviceData) {
       
    //         //seeding Service
    //         $service = $admin->services()->firstOrCreate($serviceData['service']);
            
    //         // //seeding Service Working Days
    //         foreach ($serviceData['workingDays'] as $day) {
    //             $service->days()->firstOrCreate($day);
    //         }
    //         // //seeding Service Breaks
    //         foreach ($serviceData['breaks'] as $break) {
    //             $service->breaks()->firstOrCreate($break);
    //         }
    //         // //seeding Service Holidays
    //         foreach ($serviceData['holidays'] as $holiday) {
    //             $service->holidays()->firstOrCreate($holiday);                
    //         }
        
    // }
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
                    while($slotData['remainingCapacity'] > 0) {
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
    $data  = Booking::insert($bookingsData);
    dd($data);
});