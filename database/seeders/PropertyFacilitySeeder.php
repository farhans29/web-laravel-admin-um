<?php

namespace Database\Seeders;

use App\Models\PropertyFacility;
use Illuminate\Database\Seeder;

class PropertyFacilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facilities = [
            [
                'facility' => 'Swimming Pool',
                'description' => 'A refreshing pool available for residents to use during designated hours',
                'category' => 'amenities',
                'status' => '1',
                'created_by' => '1',
            ],
            [
                'facility' => '24/7 Security',
                'description' => 'Round-the-clock security personnel to ensure safety of the property',
                'category' => 'security',
                'status' => '1',
                'created_by' => '1',
            ],
            [
                'facility' => 'Parking Area',
                'description' => 'Dedicated parking spaces for residents and visitors',
                'category' => 'general',
                'status' => '1',
                'created_by' => '1',
            ],
            [
                'facility' => 'CCTV Surveillance',
                'description' => 'Comprehensive camera coverage for enhanced security monitoring',
                'category' => 'security',
                'status' => '1',
                'created_by' => '1',
            ],
            [
                'facility' => 'WiFi Access',
                'description' => 'High-speed internet connectivity in common areas',
                'category' => 'amenities',
                'status' => '1',
                'created_by' => '1',
            ],
            [
                'facility' => 'Fitness Center',
                'description' => 'Well-equipped gym with modern exercise machines',
                'category' => 'amenities',
                'status' => '1',
                'created_by' => '1',
            ],
            [
                'facility' => 'Community Lounge',
                'description' => 'Shared space for social gatherings and events',
                'category' => 'general',
                'status' => '1',
                'created_by' => '1',
            ],
        ];

        foreach ($facilities as $facility) {
            PropertyFacility::create($facility);
        }
    }
}
