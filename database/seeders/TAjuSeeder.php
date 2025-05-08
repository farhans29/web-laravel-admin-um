<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TAju;
use App\Models\TArchive;
use App\Models\TAjuDetail;
use Carbon\Carbon;

class TAjuSeeder extends Seeder
{
    public function run()
    {
        // Seed untuk t_aju
        $aju1 = TAju::create([
            'date' => Carbon::now(),
            'id_department' => 1,
            'no_docs' => 'DOC001',
            'active_y_n' => 'Y',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        $aju2 = TAju::create([
            'date' => Carbon::now(),
            'id_department' => 2,
            'no_docs' => 'DOC002',
            'active_y_n' => 'Y',
            'created_by' => 1,
            'updated_by' => 1,
        ]);

        // Seed untuk t_archive
        $archive1 = TArchive::create([
            'idrec' => 1,
            'id_archieve' => 1,
            'date' => Carbon::now(),
            'doc_type' => 'Type A',
            'no_document' => 'DOC-A001',
            'sub_dep' => 35,
            'description' => 'Description for Archive 1',
            'file_name' => 'file1.pdf',
            'pdfblob' => 'PDF Blob Content 1',
            'active_y_n' => 'Y',
            'created_by' => 1,
            'created_at' => Carbon::now(),
            'updated_by' => 1,
            'updated_at' => Carbon::now(),
        ]);

        $archive2 = TArchive::create([
            'idrec' => 2,
            'id_archieve' => 2,
            'date' => Carbon::now(),
            'doc_type' => 'Type B',
            'no_document' => 'DOC-B002',
            'sub_dep' => 150,
            'description' => 'Description for Archive 2',
            'file_name' => 'file2.pdf',
            'pdfblob' => 'PDF Blob Content 2',
            'active_y_n' => 'Y',
            'created_by' => 1,
            'created_at' => Carbon::now(),
            'updated_by' => 1,
            'updated_at' => Carbon::now(),
        ]);

        // Seed untuk t_aju_detail
        TAjuDetail::create([
            'id_aju' => $aju1->id_aju,
            'id_archieve' => $archive1->idrec,
        ]);

        TAjuDetail::create([
            'id_aju' => $aju2->id_aju,
            'id_archieve' => $archive2->idrec,
        ]);
    }
}
