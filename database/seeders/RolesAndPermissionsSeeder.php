<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        
        $rolePermissions = [
            
            [1, 1],  
            [1, 2],  
            [1, 3],  
            [1, 4],  
            [1, 5],  
            [1, 6],  
            [1, 7],  
            [1, 8],  
            [1, 9],  
            [1, 10], 
            [1, 11], 
            [1, 12], 
            [1, 13], 
            [1, 14], 
            [1, 15],

            
            [2, 1],  
            [2, 2],  
            [2, 3],  
            [2, 4],  
            [2, 5],  
            [2, 6],  
            [2, 7],  
            [2, 8],  
            [2, 9],  
            [2, 10], 
            [2, 11], 
            [2, 12], 
            [2, 13], 
            [2, 14], 

            
            [3, 1],  
            [3, 2],  
            [3, 3],  
            [3, 4],  
            [3, 5],  
            [3, 7],  
            [3, 8],  
            [3, 10], 
            [3, 11], 

            
            [4, 1],  
            [4, 5],  
            [4, 11], 
            [4, 12], 
            [4, 13], 
            [4, 14], 

            
            [5, 1],  
            [5, 2],  
            [5, 5],  
            [5, 11], 
            [5, 13], 

            
            [6, 1],  
            [6, 2],  
            [6, 5],  
            [6, 9],  
            [6, 10], 
            [6, 11], 
            [6, 14], 

            
            [7, 1],  
            [7, 5],  
            [7, 9],  
            [7, 10], 
            [7, 14], 
        ];

        $data = array_map(function ($rolePermission) use ($now) {
            return [
                'role_id' => $rolePermission[0],
                'permission_id' => $rolePermission[1],
                'created_at' => $now,
                'updated_at' => $now,
                'created_by' => 1, 
                'updated_by' => 1,
            ];
        }, $rolePermissions);

        DB::table('role_permission')->insert($data);
    }
}
