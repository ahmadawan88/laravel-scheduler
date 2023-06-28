<?php
namespace Tests\Feature;
use App\Models\User;
use App\Models\Service;
use App\Models\Booking;
use App\Models\BusinessAdministrator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookingTest extends TestCase
{
    // use RefreshDatabase;

    public function testCreateBookings_Success()
    {
        
        $service = Service::factory()->create();
        $bookingData = [
            "service_id" =>  $service->id,
            "start_time"=> now()->format("H:i"),
            "end_time"=> now()->addMinutes("15")->format("H:i"),
            "date"=> now()->subDays(7)->format('Y-m-d'),
            "customers"=> [
                [
                    "email" => "sss2@gmail.com",
                    "first_name" => "John3",
                    "last_name" => "Doe3"
                ],
                
            ]
        ];
        $bookingData = json_encode($bookingData);
        
        $response = $this->json('POST', '/api/bookings',  json_decode($bookingData, true));
        
        $response->assertStatus(400);
    }
    

    public function testCreateBookingWithPastTimeSlot()
    {
        $service = Service::factory()->create();
        $bookingData = [
            "service_id" =>  $service->id,
            "start_time"=> now()->format("H:i"),
            "end_time"=> now()->addMinutes("15")->format("H:i"),
            "date"=> now()->subDays(7)->format('Y-m-d'),
            "customers"=> [
                [
                    "email" => "sss2@gmail.com",
                    "first_name" => "John3",
                    "last_name" => "Doe3"
                ],
                
            ]
        ];

        $response = $this->post('/bookings', $bookingData);

        $response->assertStatus(400);
    }

}
