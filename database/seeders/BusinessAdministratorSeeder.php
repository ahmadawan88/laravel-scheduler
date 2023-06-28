<?php

namespace Database\Seeders;

use App\Models\BusinessAdministrator;
use Illuminate\Database\Seeder;

class BusinessAdministratorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        BusinessAdministrator::firstOrCreate([
            'name' => config('seedSettings.mockAdminName'),
            'description' => 'N/A'
        ]);
    }
}
