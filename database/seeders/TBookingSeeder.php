<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Booking;
use Carbon\Carbon;

class MBookingSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $bookings = [
            [
                'idrec' => 1,
                'property_id' => 101,
                'order_id' => 'ORD001',
                'room_id' => 'RM101',
                'check_in_at' => $now,
                'check_out_at' => $now->copy()->addDays(2),
                'created_by' => 1,
                'created_at' => $now,
                'updated_by' => 1,
                'updated_at' => $now,
                'activeyn' => 1,
            ],
            [
                'idrec' => 2,
                'property_id' => 102,
                'order_id' => 'ORD002',
                'room_id' => 'RM102',
                'check_in_at' => $now->copy()->subDays(3),
                'check_out_at' => $now->copy()->subDay(),
                'created_by' => 2,
                'created_at' => $now,
                'updated_by' => 2,
                'updated_at' => $now,
                'activeyn' => 1,
            ],
            [
                'idrec' => 3,
                'property_id' => 103,
                'order_id' => 'ORD003',
                'room_id' => 'RM103',
                'check_in_at' => $now,
                'check_out_at' => $now->copy()->addDay(),
                'created_by' => 3,
                'created_at' => $now,
                'updated_by' => 3,
                'updated_at' => $now,
                'activeyn' => 1,
            ],
            [
                'idrec' => 4,
                'property_id' => 104,
                'order_id' => 'ORD004',
                'room_id' => 'RM104',
                'check_in_at' => $now->copy()->subDay(),
                'check_out_at' => $now->copy()->addDay(),
                'created_by' => 1,
                'created_at' => $now,
                'updated_by' => 1,
                'updated_at' => $now,
                'activeyn' => 1,
            ],
            [
                'idrec' => 5,
                'property_id' => 105,
                'order_id' => 'ORD005',
                'room_id' => 'RM105',
                'check_in_at' => $now,
                'check_out_at' => null,
                'created_by' => 2,
                'created_at' => $now,
                'updated_by' => 2,
                'updated_at' => $now,
                'activeyn' => 1,
            ],
        ];

        foreach ($bookings as $booking) {
            Booking::create($booking);
        }
    }
}
