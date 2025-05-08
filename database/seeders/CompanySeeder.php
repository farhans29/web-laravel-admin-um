<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('m_companies')->insert([
            [
                'id_company' => 1,
                'initials' => 'ABC',
                'company_type' => 'PT',
                'name' => 'Alpha Bravo',
                'address' => 'Jl. Merdeka No.1, Jakarta',
                'city' => 'Jakarta',
                'country' => 'Indonesia',
                'zip_code' => '10110',
                'npwp_id' => '01.234.567.8-901.000',
                'npwp_address' => 'Jl. Pajak No.1',
                'npwp_city' => 'Jakarta',
                'npwp_country' => 'Indonesia',
                'npwp_zipcode' => '10110',
                'npwp_pdf' => null,
                'logo_blob' => null,
                'logo_filename' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'created_by' => 1,
                'updated_by' => 1,
                'status' => 'active'
            ],
            [
                'id_company' => 2,
                'initials' => 'XYZ',
                'company_type' => 'PT',
                'name' => 'Xylophone Zebra',
                'address' => 'Jl. Mangga Dua No.5, Bandung',
                'city' => 'Bandung',
                'country' => 'Indonesia',
                'zip_code' => '40256',
                'npwp_id' => '02.345.678.9-012.000',
                'npwp_address' => 'Jl. Pajak No.2',
                'npwp_city' => 'Bandung',
                'npwp_country' => 'Indonesia',
                'npwp_zipcode' => '40256',
                'npwp_pdf' => null,
                'logo_blob' => null,
                'logo_filename' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'created_by' => 1,
                'updated_by' => 1,
                'status' => 'active'
            ],
            [
                'id_company' => 3,
                'initials' => '123',
                'company_type' => 'PT',
                'name' => 'Satu Dua Tiga',
                'address' => 'Jl. Veteran No.10, Surabaya',
                'city' => 'Surabaya',
                'country' => 'Indonesia',
                'zip_code' => '60111',
                'npwp_id' => '03.456.789.0-123.000',
                'npwp_address' => 'Jl. Pajak No.3',
                'npwp_city' => 'Surabaya',
                'npwp_country' => 'Indonesia',
                'npwp_zipcode' => '60111',
                'npwp_pdf' => null,
                'logo_blob' => null,
                'logo_filename' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'created_by' => 1,
                'updated_by' => 1,
                'status' => 'active'
            ],
            [
                'id_company' => 4,
                'initials' => 'DEF',
                'company_type' => 'CV',
                'name' => 'Delta Echo',
                'address' => 'Jl. Kebun Raya No.3, Bogor',
                'city' => 'Bogor',
                'country' => 'Indonesia',
                'zip_code' => '16122',
                'npwp_id' => '04.567.890.1-234.000',
                'npwp_address' => 'Jl. Pajak No.4',
                'npwp_city' => 'Bogor',
                'npwp_country' => 'Indonesia',
                'npwp_zipcode' => '16122',
                'npwp_pdf' => null,
                'logo_blob' => null,
                'logo_filename' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'created_by' => 1,
                'updated_by' => 1,
                'status' => 'inactive'
            ],
            [
                'id_company' => 5,
                'initials' => 'GHI',
                'company_type' => 'PT',
                'name' => 'Gamma Hotel India',
                'address' => 'Jl. Diponegoro No.7, Yogyakarta',
                'city' => 'Yogyakarta',
                'country' => 'Indonesia',
                'zip_code' => '55222',
                'npwp_id' => '05.678.901.2-345.000',
                'npwp_address' => 'Jl. Pajak No.5',
                'npwp_city' => 'Yogyakarta',
                'npwp_country' => 'Indonesia',
                'npwp_zipcode' => '55222',
                'npwp_pdf' => null,
                'logo_blob' => null,
                'logo_filename' => null,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'created_by' => 1,
                'updated_by' => 1,
                'status' => 'active'
            ],
        ]);
    }
}
