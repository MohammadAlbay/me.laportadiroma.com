<?php

namespace Database\Seeders;

use App\Models\Role as ModelsRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class Role extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        ModelsRole::create([ /* id 1 */
            'name' => "Admin",
            "visible_name" => "Administrator",
        ]);

        ModelsRole::create([ /* id 2 */
            'name' => "Employee",
            "visible_name" => "Employee",
        ]);
    }
}
