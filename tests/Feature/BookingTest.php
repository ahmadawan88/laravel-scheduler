<?php
namespace Tests\Feature;
// use App\Models\User;
// use App\Models\Service;
// use App\Models\Booking;
// use Carbon\Carbon;
// use Illuminate\Foundation\Testing\RefreshDatabase;
// use Illuminate\Foundation\Testing\WithFaker;
// use Illuminate\Support\Facades\DB;
// use Tests\TestCase;

// class BookingTest extends TestCase
// {
//     use RefreshDatabase, WithFaker;

//     /**
//      * Test creating a booking.
//      *
//      * @return void
//      */
//     public function testCreateBooking()
//     {
//         // Create a business administrator user
//         $businessAdmin = User::create([
//             'name' => 'Mock Admin',
//             'email' => 'admin@example.com',
//             'password' => bcrypt('password'),
//         ]);

//         // Create a service
//         $service = Service::create([
//             'name' => 'Men Haircut',
//             'slot_duration' => 10,
//             'capacity' => 3,
//             'clean_time' => 5,
//             'booking_time_limit' => 7,
//             'business_administrator_id' => $businessAdmin->id,
//         ]);

//         // Create a booking
//         $booking = Booking::create([
//             'user_id' => $businessAdmin->id,
//             'service_id' => $service->id,
//             'start_time' => Carbon::now()->addHours(1),
//             'end_time' => Carbon::now()->addHours(2),
//         ]);

//         // Assert the booking was created successfully
//         $this->assertDatabaseHas('bookings', [
//             'id' => $booking->id,
//             'user_id' => $businessAdmin->id,
//             'service_id' => $service->id,
//         ]);
//     }

//     /**
//      * Test creating a booking with a past time slot.
//      *
//      * @return void
//      */
//     public function testCreateBookingWithPastTimeSlot()
//     {
//         // Create a business administrator user
//         $businessAdmin = User::create([
//             'name' => 'Mock Admin',
//             'email' => 'admin@example.com',
//             'password' => bcrypt('password'),
//         ]);

//         // Create a service
//         $service = Service::create([
//             'name' => 'Men Haircut',
//             'slot_duration' => 10,
//             'capacity' => 3,
//             'clean_time' => 5,
//             'booking_time_limit' => 7,
//             'business_administrator_id' => $businessAdmin->id,
//         ]);

//         // Create a booking with a past time slot
//         $booking = Booking::create([
//             'user_id' => $businessAdmin->id,
//             'service_id' => $service->id,
//             'start_time' => Carbon::now()->subHours(2),
//             'end_time' => Carbon::now()->subHours(1),
//         ]);

//         // Assert the booking was not created
//         $this->assertDatabaseMissing('bookings', ['id' => $booking->id]);
//     }

//     /**
//      * Test creating a booking with validation failure.
//      *
//      * @return void
//      */
//     public function testCreateBookingWithValidationFailure()
//     {
//         // Create a business administrator user
//         $businessAdmin = User::create([
//             'name' => 'Mock Admin',
//             'email' => 'admin@example.com',
//             'password' => bcrypt('password'),
//         ]);

//         // Create a service
//         $service = Service::create([
//             'name' => 'Men Haircut',
//             'slot_duration' => 10,
//             'capacity' => 3,
//             'clean_time' => 5,
//             'booking_time_limit' => 7,
//             'business_administrator_id' => $businessAdmin->id,
//         ]);

//         // Create a booking with validation failure (end time before start time)
//         $booking = Booking::create([
//             'user_id' => $businessAdmin->id,
//             'service_id' => $service->id,
//             'start_time' => Carbon::now()->addHours(2),
//             'end_time' => Carbon::now()->addHours(1),
//         ]);

//         // Assert the booking was not created
//         $this->assertDatabaseMissing('bookings', ['id' => $booking->id]);
//     }
// }


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
    use DatabaseMigrations, RefreshDatabase;

    public function testCreateBooking()
    {
        // $user = User::factory()->create();
        $user = User::create([
                        'first_name' => 'John',
                        'last_name' => 'Doe',
                        'email' => 'test@example.com',
                        'password' => bcrypt('password'),
                    ]);
        $businessAdmin = BusinessAdministrator::create([
            'name' => 'Mock Admin',
        ]);
        // Create a service
        $service = Service::create([
            'name' => 'Men Haircut',
            'slot_duration' => 10,
            'capacity' => 3,
            'clean_time' => 5,
            'booking_time_limit' => 7,
            'business_administrator_id' => $businessAdmin->id,
        ]);
        $bookingData = [
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'date' => '2023-06-28',
            'service_id' => $service->id,
            'user_id' => $user->id,
        ];

        $response = $this->post('/bookings', $bookingData);

        $response->assertStatus(200);

        $this->assertDatabaseHas('bookings', $bookingData);
    }

    public function testCreateBookingWithPastTimeSlot()
    {
        $user = User::factory()->create();
        $service = Service::factory()->create();

        $bookingData = [
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'date' => '2023-06-28',
            'service_id' => $service->id,
            'user_id' => $user->id,
        ];

        $response = $this->post('/bookings', $bookingData);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'start_time' => ['The selected start time is invalid.'],
                ],
            ]);
    }

    public function testCreateBookingWithValidationFailure()
    {
        $user = User::factory()->create();
        $service = Service::factory()->create();

        $bookingData = [
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'date' => '2023-06-28',
            'service_id' => $service->id,
            'user_id' => $user->id,
        ];

        // Create a booking
        Booking::factory()->create($bookingData);

        $response = $this->post('/bookings', $bookingData);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'date' => ['The selected date is invalid.'],
                ],
            ]);
    }
}
