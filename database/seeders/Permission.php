<?php

namespace Database\Seeders;

use App\Models\Permission as ModelsPermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Permission extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $this->generateBasePermissions();
    }

    private function generateBasePermissions() {
        ModelsPermission::create([
            "name"=> "Login",
            "visible_name" => "Log into the system",
            "state" => "Active",
            "category" => "Login",
            "target" => "Allow"
        ]);
    }
}
