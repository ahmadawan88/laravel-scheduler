<?php

namespace Database\Seeders;

use App\Models\BusinessAdministrator;
use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Getting Mock BusinessAdministrator
        $admin = BusinessAdministrator::where('name', config('seedSettings.mockAdminName'))->first();
    
        $serviceSeedData = config('seedSettings.serviceSeedData');
        foreach ($serviceSeedData as $serviceData) {
            $service = $this->seedService($admin, $serviceData['service']);
            $this->seedWorkingDays($service, $serviceData['workingDays']);
            $this->seedBreaks($service, $serviceData['breaks']);
            $this->seedHolidays($service, $serviceData['holidays']);
        }
    }
    
    private function seedService(BusinessAdministrator $admin, array $serviceData): Service
    {
        return $admin->services()->firstOrCreate($serviceData);
    }
    
    private function seedWorkingDays(Service $service, array $workingDays): void
    {
        foreach ($workingDays as $day) {
            $service->days()->firstOrCreate($day);
        }
    }
    
    private function seedBreaks(Service $service, array $breaks): void
    {
        foreach ($breaks as $break) {
            $service->breaks()->firstOrCreate($break);
        }
    }
    
    private function seedHolidays(Service $service, array $holidays): void
    {
        foreach ($holidays as $holiday) {
            $service->holidays()->firstOrCreate($holiday);
        }
    }
    
}
