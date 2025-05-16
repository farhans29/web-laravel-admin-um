<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;
use Carbon\Carbon;

class MRoomsSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $rooms = [
            [
                'idrec' => 1,
                'property_id' => 1,
                'property_name' => 'Villa Bali Asri',
                'slug' => 'room-deluxe-bali',
                'name' => 'Deluxe Room',
                'descriptions' => 'Kamar luas dengan balkon menghadap laut.',
                'periode' => json_encode(['checkin' => '14:00', 'checkout' => '12:00']),
                'type' => 'Deluxe',
                'level' => '2',
                'facility' => json_encode(['AC', 'Wi-Fi', 'TV', 'Mini Bar']),
                'price' => json_encode(['weekday' => 800000, 'weekend' => 1000000]),
                'attachment' => null,
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' => 'admin',
                'updated_by' => 'admin',
                'status' => 1,
            ],
            [
                'idrec' => 2,
                'property_id' => 1,
                'property_name' => 'Villa Bali Asri',
                'slug' => 'room-standard-bali',
                'name' => 'Standard Room',
                'descriptions' => 'Kamar nyaman cocok untuk pasangan.',
                'periode' => json_encode(['checkin' => '15:00', 'checkout' => '11:00']),
                'type' => 'Standard',
                'level' => '1',
                'facility' => json_encode(['Fan', 'TV']),
                'price' => json_encode(['weekday' => 500000, 'weekend' => 700000]),
                'attachment' => null,
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' => 'admin',
                'updated_by' => 'admin',
                'status' => 1,
            ],
        ];

        foreach ($rooms as $room) {
            Room::create($room);
        }
    }
}
