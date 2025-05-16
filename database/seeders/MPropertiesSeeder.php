<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Property;
use Carbon\Carbon;

class MPropertiesSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $data = [
            [
                'idrec' => 1,
                'slug' => 'villa-bali',
                'tags' => 'villa,pantai',
                'name' => 'Villa Bali Asri',
                'description' => 'Penginapan nyaman di tepi pantai.',
                'province' => 'Bali',
                'city' => 'Denpasar',
                'subdistrict' => 'Kuta',
                'village' => 'Seminyak',
                'postal_code' => '80361',
                'address' => 'Jl. Pantai Kuta No.88',
                'location' => '-8.715, 115.168',
                'distance' => '500m dari pantai',
                'price' => json_encode(['weekday' => 1000000, 'weekend' => 1500000]),
                'features' => json_encode(['wifi', 'pool', 'ac']),
                'attributes' => json_encode(['max_guest' => 4, 'bedrooms' => 2]),
                'image' => null,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' => 'admin',
                'updated_by' => 'admin',
            ],
            [
                'idrec' => 2,
                'slug' => 'apartment-jakarta',
                'tags' => 'apartment,kota',
                'name' => 'Jakarta Central Apartment',
                'description' => 'Apartemen modern di pusat Jakarta.',
                'province' => 'DKI Jakarta',
                'city' => 'Jakarta Pusat',
                'subdistrict' => 'Menteng',
                'village' => 'Menteng Atas',
                'postal_code' => '10310',
                'address' => 'Jl. Sudirman No.10',
                'location' => '-6.2088, 106.8456',
                'distance' => '100m dari stasiun',
                'price' => json_encode(['monthly' => 7000000]),
                'features' => json_encode(['lift', 'security', 'parking']),
                'attributes' => json_encode(['floor' => 15, 'rooms' => 3]),
                'image' => null,
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' => 'admin',
                'updated_by' => 'admin',
            ],
            // Tambahkan data ke-3, 4, 5 sesuai kebutuhan...
        ];

        foreach ($data as $item) {
            Property::create($item);
        }
    }
}
