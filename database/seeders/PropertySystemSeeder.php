<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class PropertySystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Clear existing data
        DB::table('t_booking')->delete();
        DB::table('t_transactions')->delete();
        DB::table('t_payment')->delete();
        DB::table('m_rooms')->delete();
        DB::table('m_properties')->delete();

        // Seed m_properties
        $properties = [];
        $propertyNames = [
            'Jelambar House 1',
            'Jelambar House 2',
            'Ulin Mahoni House Jaksel',
            'Ulin Mahoni House Jaksel 2',
            'Jelambar House',
            'Jelambar House I',
            'Ulin Mahoni Apartment',
            'Ulin Mahoni Apartment I',
            'Ulin Mahoni Villa',
            'Ulin Mahoni Villa I'
        ];
        $tags = ['House', 'Apartment', 'Villa', 'Hotel'];

        $locations = [
            'DKI Jakarta' => ['Jakarta Pusat', 'Jakarta Utara', 'Jakarta Barat', 'Jakarta Selatan', 'Jakarta Timur'],
            'Jawa Barat' => ['Bandung', 'Bekasi', 'Depok', 'Bogor', 'Cimahi'],
            'Jawa Tengah' => ['Semarang', 'Solo', 'Yogyakarta', 'Magelang', 'Salatiga'],
            'Jawa Timur' => ['Surabaya', 'Malang', 'Kediri', 'Blitar', 'Madiun'],
            'Bali' => ['Denpasar', 'Ubud', 'Sanur', 'Kuta', 'Canggu']
        ];

        $amenities = json_encode(['High-speed WiFi', 'Parking', 'Swimming Pool', 'Gym', 'Restaurant']);
        $features = json_encode(['24/7 Security', 'Concierge', 'Laundry Service', 'Room Service']);
        $rules = json_encode(['No Smoking', 'No Pets', 'Check-in after 2PM', 'Check-out before 12PM', 'ID Card Required', 'Deposit Required']);

        $i = 1;
        foreach ($locations as $province => $cityList) {
            $city = $cityList[array_rand($cityList)];

            $properties[$i] = [
                'idrec' => $i,
                'slug' => Str::slug($propertyNames[$i - 1]),
                'tags' => implode(',', array_slice($tags, 0, rand(1, 3))),
                'name' => $propertyNames[$i - 1],
                'initial' => substr($propertyNames[$i - 1], 0, 3),
                'description' => 'Luxurious accommodation with premium amenities and excellent service.',
                'level_count' => rand(5, 20),
                'province' => $province,
                'city' => $city,
                'subdistrict' => 'Subdistrict ' . $i,
                'village' => 'Village ' . $i,
                'postal_code' => '1000' . $i,
                'address' => 'Jl. Example No.' . $i . ', ' . $city,
                'location' => 'https://maps.google.com/?q=' . $i,                
                'price' => json_encode(['daily' => rand(500000, 2000000), 'monthly' => rand(10000000, 30000000)]),
                'price_original_daily' => rand(500000, 2000000),
                'price_discounted_daily' => rand(400000, 1800000),
                'price_original_monthly' => rand(10000000, 30000000),
                'price_discounted_monthly' => rand(9000000, 28000000),
                'features' => $features,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
                'created_by' => '1',
                'updated_by' => '1'
            ];
            $i++;
        }

        DB::table('m_properties')->insert($properties);

        // Seed m_rooms
        $rooms = [];
        $roomTypes = ['Deluxe', 'Superior', 'Executive', 'Suite', 'Presidential'];
        $roomCounter = 1;

        foreach ($properties as $propertyId => $property) {
            $roomCount = rand(3, 5); // 3-5 rooms per property
            for ($j = 1; $j <= $roomCount; $j++) {
                $dailyPrice = rand(500000, 2000000);
                $monthlyPrice = rand(10000000, 30000000);

                $rooms[] = [
                    'idrec' => $roomCounter, // ID record unik
                    'property_id' => $propertyId, // ID properti
                    'property_name' => $property['name'], // Nama properti
                    'slug' => Str::slug($property['name'] . ' ' . $roomTypes[$j - 1] . ' Room'), // URL-friendly slug
                    'name' => $roomTypes[$j - 1] . ' Room', // Nama kamar
                    'descriptions' => 'Comfortable ' . $roomTypes[$j - 1] . ' room with all amenities', // Deskripsi
                    'periode' => json_encode([
                        'daily' => $dailyPrice,
                        'monthly' => $monthlyPrice
                    ]), // Harga per periode (disimpan dalam JSON)
                    'bed_type' => $roomTypes[$j - 1], // Tipe tempat tidur
                    'capacity' => rand(1, 4), // Kapasitas kamar
                    'size' => rand(20, 50), // Ukuran kamar dalam m2
                    'type' => $roomTypes[$j - 1], // Tipe kamar
                    'no' => 'R' . str_pad($roomCounter, 3, '0', STR_PAD_LEFT), // Nomor kamar
                    'slug' => Str::slug($property['name'] . ' ' . $roomTypes[$j - 1] . ' Room'), // Slug untuk URL
                    'level' => rand(1, $property['level_count']), // Random lantai
                    'facility' => json_encode([
                        'AC',
                        'TV',
                        'Minibar',
                        'Safe',
                        'Private Bathroom'
                    ]), // Fasilitas dalam bentuk JSON
                    'price' => json_encode([
                        'original_daily' => $dailyPrice,
                        'discounted_daily' => $dailyPrice * 0.9,
                        'original_monthly' => $monthlyPrice,
                        'discounted_monthly' => $monthlyPrice * 0.85
                    ]), // Harga diskon
                    'status' => 1, // Aktif
                    'created_at' => now(),
                    'updated_at' => now(),
                    'created_by' => 1,
                    'updated_by' => 1
                ];

                $roomCounter++;
            }
        }

        DB::table('m_rooms')->insert($rooms);

        // Seed t_transactions, t_booking, and t_payment
        $transactions = [];
        $bookings = [];
        $payments = [];        

        for ($k = 1; $k <= 10; $k++) {
            $userId = rand(1, 22);
            $propertyId = rand(1, 5); // Acak 5 properti
            $property = $properties[$propertyId];

            // Get random room from this property
            $propertyRooms = array_filter($rooms, function ($room) use ($propertyId) {
                return $room['property_id'] == $propertyId;
            });
            $room = $propertyRooms[array_rand($propertyRooms)];

            $orderId = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
            $checkIn = now()->addDays(rand(1, 30))->setTime(14, 0);
            $checkOut = (clone $checkIn)->addDays(rand(1, 14))->setTime(12, 0);
            $bookingDays = $checkIn->diffInDays($checkOut);

            $price = json_decode($room['price'], true);
            $dailyPrice = $price['discounted_daily'] ?? $price['original_daily'] ?? 0;
            $roomPrice = $dailyPrice * $bookingDays;
            $adminFees = $roomPrice * 0.1;
            $grandTotal = $roomPrice + $adminFees;

            $transactions[] = [
                'property_id' => (string)$propertyId,
                'room_id' => (string)$room['idrec'],
                'order_id' => $orderId,
                'user_id' => $userId,
                'user_name' => 'User ' . $userId,
                'user_phone_number' => '0812' . rand(1000000, 9999999),
                'property_name' => $property['name'],
                'transaction_date' => now(),
                'check_in' => $checkIn,
                'check_out' => $checkOut,
                'room_name' => $room['name'],
                'user_email' => 'user' . $userId . '@example.com',
                'booking_days' => $bookingDays,
                'booking_months' => null,
                'daily_price' => $dailyPrice,
                'room_price' => $roomPrice,
                'admin_fees' => $adminFees,
                'grandtotal_price' => $grandTotal,
                'property_type' => 'Hotel',
                'transaction_type' => null,
                'transaction_code' => 'TRX-' . strtoupper(Str::random(8)),
                'transaction_status' => 'pending', // belum diproses
                'status' => '1',
                'paid_at' => null,                
                'notes' => 'Booking belum dibayar',
                'created_at' => now(),
                'updated_at' => now()
            ];

            $bookings[] = [
                'property_id' => $propertyId,
                'order_id' => $orderId,
                'room_id' => (string)$room['idrec'],
                'check_in_at' => null, // belum check in
                'check_out_at' => null, // belum check out
                'created_by' => $userId,
                'updated_by' => $userId,
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ];

            $payments[] = [
                'order_id'          => $orderId,
                'user_id'           => $userId,
                'grandtotal_price'  => $grandTotal,
                'verified_by'       => null,
                'verified_at'       => null,
                'notes'             => 'Pembayaran untuk booking ' . $orderId,
                'payment_status'    => 'unpaid',
                'created_at'        => now(),
                'updated_at'        => now(),
                'created_by'        => $userId
            ];
        }

        DB::table('t_transactions')->insert($transactions);
        DB::table('t_booking')->insert($bookings);
        DB::table('t_payment')->insert($payments);
    }
}
