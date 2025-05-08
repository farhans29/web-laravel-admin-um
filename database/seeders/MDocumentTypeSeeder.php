<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MDocumentType;

class MDocumentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['code' => 'INV', 'name' => 'Invoice'],
            ['code' => 'PO', 'name' => 'Purchase Order'],
            ['code' => 'DO', 'name' => 'Delivery Order'],
            ['code' => 'CTR', 'name' => 'Contract'],
            ['code' => 'PRP', 'name' => 'Proposal'],
            ['code' => 'RPT', 'name' => 'Report'],
            ['code' => 'MMO', 'name' => 'Memo'],
            ['code' => 'AGR', 'name' => 'Agreement'],
            ['code' => 'RCT', 'name' => 'Receipt'],
            ['code' => 'MGD', 'name' => 'Manual Guide'],
            ['code' => 'PLD', 'name' => 'Policy Document'],
            ['code' => 'TSP', 'name' => 'Technical Specification'],
            ['code' => 'MMT', 'name' => 'Meeting Minutes'],
            ['code' => 'CRT', 'name' => 'Certification'],
            ['code' => 'LGD', 'name' => 'Legal Document'],
        ];

        foreach ($data as $item) {
            MDocumentType::create([
                'code' => $item['code'],
                'name' => $item['name'],
                'status' => 'Active',
                'created_by' => 'admin',
            ]);
        }
    }
}
