<?php

namespace Database\Seeders;

use App\Models\RolePermission as ModelsRolePermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RolePermission extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        ModelsRolePermission::create([
            "role_id" => 1,
            "permission_id" => 1
        ]);
    }
}
