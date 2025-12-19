<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Drop existing foreign key
        Schema::table('role_permission', function (Blueprint $table) {
            $table->dropForeign(['permission_id']);
        });

        // Step 2: Get all users and their role permissions
        $users = DB::table('users')->whereNotNull('role_id')->get();
        $userPermissions = [];

        foreach ($users as $user) {
            $rolePermissions = DB::table('role_permission')
                ->where('role_id', $user->role_id)
                ->get();

            foreach ($rolePermissions as $rolePermission) {
                $key = $user->id . '_' . $rolePermission->permission_id;
                if (!isset($userPermissions[$key])) {
                    $userPermissions[$key] = [
                        'user_id' => $user->id,
                        'permission_id' => $rolePermission->permission_id,
                        'created_by' => $rolePermission->created_by,
                        'updated_by' => $rolePermission->updated_by,
                        'created_at' => $rolePermission->created_at ?? now(),
                        'updated_at' => $rolePermission->updated_at ?? now(),
                    ];
                }
            }
        }

        // Step 3: Clear the table
        DB::table('role_permission')->truncate();

        // Step 4: Drop primary key
        Schema::table('role_permission', function (Blueprint $table) {
            $table->dropPrimary(['role_id', 'permission_id']);
        });

        // Step 5: Rename column role_id to user_id
        Schema::table('role_permission', function (Blueprint $table) {
            $table->renameColumn('role_id', 'user_id');
        });

        // Step 6: Add new primary key
        Schema::table('role_permission', function (Blueprint $table) {
            $table->primary(['user_id', 'permission_id']);
        });

        // Step 7: Insert migrated data
        if (!empty($userPermissions)) {
            DB::table('role_permission')->insert(array_values($userPermissions));
        }

        // Step 8: Add foreign key constraints
        Schema::table('role_permission', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign keys
        Schema::table('role_permission', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['permission_id']);
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);
        });

        // Drop primary key
        Schema::table('role_permission', function (Blueprint $table) {
            $table->dropPrimary(['user_id', 'permission_id']);
        });

        // Rename column back
        Schema::table('role_permission', function (Blueprint $table) {
            $table->renameColumn('user_id', 'role_id');
        });

        // Add primary key back
        Schema::table('role_permission', function (Blueprint $table) {
            $table->primary(['role_id', 'permission_id']);
        });

        // Add foreign keys back
        Schema::table('role_permission', function (Blueprint $table) {
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }
};
