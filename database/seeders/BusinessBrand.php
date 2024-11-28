<?php

namespace Database\Seeders;

use App\Models\BusinessBrand as ModelsBusinessBrand;
use App\Models\Role as ModelsRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BusinessBrand extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //

        ModelsBusinessBrand::create([ /* id 1 */
            'name' => "Holasaci",
            'address' => "طرابلس, وسعاية البديري",
            'floor'=> "0",
            'base_route' => "/holasaci",
            'profile' => ""
        ]);

        ModelsBusinessBrand::create([ /* id 2 */
            'name' => "La Porta Di Roma",
            'address' => "طرابلس, وسعاية البديري",
            'base_route' => "/dashboard",
            'floor'=> "3",
            'profile' => ""
        ]);
    }
}
